<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Income;
use App\Models\Invoice;
use App\Models\Loan;
use App\Models\Payroll;
use App\Models\Purchasing;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PDF;

class TaxReportController extends Controller
{
    private function calculateReport($startDate, $endDate)
    {
        // Validate date range
        if (! $startDate || ! $endDate || $startDate > $endDate) {
            throw new InvalidArgumentException('Invalid date range provided');
        }

        // Convert to Carbon for consistent date handling
        $startDate = \Carbon\Carbon::parse($startDate)->startOfDay();
        $endDate = \Carbon\Carbon::parse($endDate)->endOfDay();

        // Fetch data with proper relationships and date filtering
        $incomes = Income::whereBetween('date', [$startDate, $endDate])->get();
        $purchasings = Purchasing::whereBetween('date', [$startDate, $endDate])->get();
        $payrolls = Payroll::with('employee')
            ->whereBetween('pay_date', [$startDate, $endDate])
            ->get();
        $loans = Loan::whereBetween('created_at', [$startDate, $endDate])->get();
        $invoices = Invoice::whereBetween('due_date', [$startDate, $endDate])
            ->where('is_pending', true)
            ->get();

        // Calculate PPN (VAT)
        $totalIncome = $incomes->sum('amount') ?? 0;
        $ppn = round($totalIncome * 0.11, 2);

        // Calculate PPh21 (Personal Income Tax)
        $pph21 = 0;
        foreach ($payrolls as $payroll) {
            $monthlySalary = ($payroll->employee->salary ?? 0) + ($payroll->allowance ?? 0) - ($payroll->deduction ?? 0);
            $annualSalary = $monthlySalary * 12;
            $ptkp = 54000000; // Individual PTKP
            $pkp = max(0, $annualSalary - $ptkp);

            if ($pkp <= 60000000) {
                $pph21 += $pkp * 0.05;
            } elseif ($pkp <= 250000000) {
                $pph21 += 60000000 * 0.05 + ($pkp - 60000000) * 0.15;
            } elseif ($pkp <= 500000000) {
                $pph21 += 60000000 * 0.05 + 190000000 * 0.15 + ($pkp - 250000000) * 0.25;
            } else {
                $pph21 += 60000000 * 0.05 + 190000000 * 0.15 + 250000000 * 0.25 + ($pkp - 500000000) * 0.3;
            }
        }
        $pph21 = round($pph21, 2);

        // Calculate HPP and Gross Profit
        $hpp = $purchasings->sum('total_price') ?? 0;
        $labaKotor = round($totalIncome - $hpp, 2);

        // Calculate total payroll expenses
        $totalGaji = $payrolls->sum(function ($payroll) {
            return ($payroll->employee->salary ?? 0) + ($payroll->allowance ?? 0) - ($payroll->deduction ?? 0);
        });

        // Calculate interest expenses
        $bungaPendek = $loans->sum(function ($loan) {
            if ($loan->installments > 0) {
                $totalBunga = ($loan->principal * ($loan->interest_rate ?? 0)) / 100;

                return round($totalBunga / $loan->installments, 2);
            }

            return 0;
        });

        $bungaPanjang = $loans->sum(function ($loan) {
            if ($loan->installments > 0) {
                $totalBunga = ($loan->principal * ($loan->interest_rate ?? 0)) / 100;

                return round($totalBunga - $totalBunga / $loan->installments, 2);
            }

            return 0;
        });

        // Calculate net profit
        $bebanUsaha = round($totalGaji + $bungaPendek, 2);
        $labaBersih = round($labaKotor - $bebanUsaha, 2);

        // Calculate corporate tax (PPh Badan)
        $pphBadan = $labaBersih > 0 ? round($labaBersih * 0.22, 2) : 0;

        // Calculate total tax obligations
        $totalSPT = round($ppn + $pph21 + $pphBadan, 2);

        // Calculate receivables and payables
        $piutangUsaha = $invoices->sum('grand_total') ?? 0;
        $utangUsahaCicilan = $loans->sum('monthly_installment') ?? 0;

        // Prepare balance sheet
        $aktivaLancar = [
            'Kas' => $totalIncome,
            'Persediaan' => $hpp,
            'Piutang Usaha' => $piutangUsaha,
        ];

        $aktivaTetap = [
            'Aktiva Tetap' => $hpp, // Note: This might need adjustment based on actual fixed assets
        ];

        $totalAktiva = round(array_sum($aktivaLancar) + array_sum($aktivaTetap), 2);

        $kewajibanPendek = [
            'Utang Usaha (Cicilan Bulanan)' => $utangUsahaCicilan,
            'Bunga Terutang Jangka Pendek' => $bungaPendek,
            'PPN Terutang' => $ppn,
            'PPh21 Terutang' => $pph21,
            'PPh Badan Terutang' => $pphBadan,
        ];

        $kewajibanPanjang = [
            'Pinjaman Bank' => $loans->sum('principal') ?? 0,
            'Bunga Terutang Jangka Panjang' => $bungaPanjang,
        ];

        $totalKewajiban = round(array_sum($kewajibanPendek) + array_sum($kewajibanPanjang), 2);
        $ekuitas = round($totalAktiva - $totalKewajiban, 2);

        return array_merge(compact('startDate', 'endDate', 'totalIncome', 'hpp', 'labaKotor', 'bebanUsaha', 'labaBersih', 'ppn', 'pph21', 'pphBadan', 'totalSPT', 'aktivaLancar', 'aktivaTetap', 'totalAktiva', 'kewajibanPendek', 'kewajibanPanjang', 'totalKewajiban', 'ekuitas'), [
            'aset' => $totalAktiva,
            'kewajiban' => $totalKewajiban,
        ]);
    }

    public function index(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfYear()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfYear()->toDateString());

        $data = $this->calculateReport($startDate, $endDate);

        return view('accounting.tax.index', $data);
    }

    public function exportPdf(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfYear()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfYear()->toDateString());

        $data = $this->calculateReport($startDate, $endDate);

        // Logo base64
        $logoPath = public_path('assets/img/logo.png');
        $logoBase64 = file_exists($logoPath) ? 'data:image/png;base64,'.base64_encode(file_get_contents($logoPath)) : null;

        // Info perusahaan
        $data['company'] = [
            'name' => 'PT. Jowoland Construction',
            'address' => 'Ketitang, Godong, Grobogan, Jawa Tengah',
            'phone' => '0852-8074-9218',
            'email' => 'info@jowolandborepile.com',
            'npwp' => '01.234.567.8-999.000',
            'logo' => $logoBase64,
        ];

        // Surat
        $data['nomor_surat'] = 'SPT/'.date('Y').'/'.rand(100, 999);
        $data['tanggal_terbit'] = Carbon::now()->translatedFormat('d F Y');
        $data['startDate'] = $startDate;
        $data['endDate'] = $endDate;

        $pdf = PDF::loadView('accounting.tax.pdf', $data)->setPaper('A4', 'portrait');

        return $pdf->download('laporan_pajak_resmi_'.$startDate.'_to_'.$endDate.'.pdf');
    }
}

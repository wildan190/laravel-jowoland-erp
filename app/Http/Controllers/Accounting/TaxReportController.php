<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Income;
use App\Models\Purchasing;
use App\Models\Payroll;
use App\Models\Loan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Deal;
use PDF;

class TaxReportController extends Controller
{
    private function calculateReport($startDate, $endDate)
{
    $incomes = Income::whereBetween('date', [$startDate, $endDate])->get();
    $purchasings = Purchasing::whereBetween('date', [$startDate, $endDate])->get();
    $payrolls = Payroll::with('employee')
        ->whereBetween('pay_date', [$startDate, $endDate])
        ->get();
    $loans = Loan::all();

    // === PPN ===
    $totalIncome = $incomes->sum('amount');
    $ppn = $totalIncome * 0.11;

    // === PPh21 ===
    $pph21 = 0;
    foreach ($payrolls as $p) {
        $gajiBulanan = ($p->employee->salary ?? 0) + ($p->allowance ?? 0) - ($p->deduction ?? 0);
        $gajiTahunan = $gajiBulanan * 12;
        $ptkp = 54000000; // PTKP individu
        $pkp = max(0, $gajiTahunan - $ptkp);

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

    // === HPP & Laba ===
    $hpp = $purchasings->sum('total_price');
    $labaKotor = $totalIncome - $hpp;

    $totalGaji = $payrolls->sum(function ($p) {
        return ($p->employee->salary ?? 0) + ($p->allowance ?? 0) - ($p->deduction ?? 0);
    });

    // === Bunga Terutang ===
    $bungaPendek = $loans->sum(function ($loan) {
        if ($loan->installments > 0) {
            $totalBunga = ($loan->principal * $loan->interest_rate) / 100;
            return $totalBunga / $loan->installments;
        }
        return 0;
    });

    $bungaPanjang = $loans->sum(function ($loan) {
        $totalBunga = ($loan->principal * $loan->interest_rate) / 100;
        if ($loan->installments > 0) {
            return $totalBunga - ($totalBunga / $loan->installments);
        }
        return 0;
    });

    $bebanUsaha = $totalGaji + $bungaPendek;
    $labaBersih = $labaKotor - $bebanUsaha;

    // === PPh Badan ===
    $pphBadan = $labaBersih > 0 ? $labaBersih * 0.22 : 0;

    // === Total SPT ===
    $totalSPT = $ppn + $pph21 + $pphBadan;

    // === Piutang & Utang Usaha ===
    $piutangUsaha = $incomes->where('status', 'unpaid')->sum('amount');
    $utangUsahaCicilan = $loans->sum('monthly_installment'); // hanya cicilan bulanan

    // === Neraca ===
    $aktivaLancar = [
        'Kas' => $totalIncome,
        'Persediaan' => $hpp,
        'Piutang Usaha' => $piutangUsaha,
    ];

    // Aktiva Tetap = Persediaan
    $aktivaTetap = [
        'Aktiva Tetap' => $hpp,
    ];

    $totalAktiva = array_sum($aktivaLancar) + array_sum($aktivaTetap);

    $kewajibanPendek = [
        'Utang Usaha (Cicilan Bulanan)' => $utangUsahaCicilan,
        'Bunga Terutang Jangka Pendek' => $bungaPendek,
    ];

    $kewajibanPanjang = [
        'Pinjaman Bank' => $loans->sum('principal'),
        'Bunga Terutang Jangka Panjang' => $bungaPanjang,
    ];

    $totalKewajiban = array_sum($kewajibanPendek) + array_sum($kewajibanPanjang);
    $ekuitas = $totalAktiva - $totalKewajiban;

    return array_merge(
        compact(
            'startDate',
            'endDate',
            'totalIncome',
            'hpp',
            'labaKotor',
            'bebanUsaha',
            'labaBersih',
            'ppn',
            'pph21',
            'pphBadan',
            'totalSPT',
            'aktivaLancar',
            'aktivaTetap',
            'totalAktiva',
            'kewajibanPendek',
            'kewajibanPanjang',
            'totalKewajiban',
            'ekuitas'
        ),
        [
            'aset' => $totalAktiva,
            'kewajiban' => $totalKewajiban,
        ]
    );
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
        $logoBase64 = file_exists($logoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath)) : null;

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
        $data['nomor_surat'] = 'SPT/' . date('Y') . '/' . rand(100, 999);
        $data['tanggal_terbit'] = Carbon::now()->translatedFormat('d F Y');
        $data['startDate'] = $startDate;
        $data['endDate'] = $endDate;

        $pdf = PDF::loadView('accounting.tax.pdf', $data)->setPaper('A4', 'portrait');

        return $pdf->download('laporan_pajak_resmi_' . $startDate . '_to_' . $endDate . '.pdf');
    }
}

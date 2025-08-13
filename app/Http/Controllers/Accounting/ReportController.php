<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Income;
use App\Models\Payroll;
use App\Models\Purchasing;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Ambil filter tanggal dari request
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        // Ambil data berdasarkan rentang tanggal
        $incomes = Income::whereBetween('date', [$startDate, $endDate])->get();
        $purchasings = Purchasing::whereBetween('date', [$startDate, $endDate])->get();
        $payrolls = Payroll::whereBetween('pay_date', [$startDate, $endDate])->get();

        // Hitung total
        $totalIncome = $incomes->sum('amount');
        $totalPurchasing = $purchasings->sum('total_price');

        // Perbaikan: Hitung total salary dengan mengurangi deduction
        $totalSalary = $payrolls->sum(function ($payroll) {
            $basicSalary = $payroll->employee->salary ?? 0;
            $allowance = $payroll->allowance ?? 0;
            $deduction = $payroll->deduction ?? 0;

            return $basicSalary + $allowance - $deduction;
        });

        $totalExpense = $totalPurchasing; // Hanya pembelian, tidak termasuk gaji
        $balance = $totalIncome - $totalExpense - $totalSalary; // Pemasukan - Pengeluaran - Gaji

        return view('accounting.reports.index', compact('startDate', 'endDate', 'incomes', 'purchasings', 'payrolls', 'totalIncome', 'totalPurchasing', 'totalSalary', 'totalExpense', 'balance'));
    }

    public function annualReport(Request $request)
    {
        $year = $request->get('year', now()->year);

        // Ambil data income & purchasing langsung
        $incomes = Income::with('deal.contact')->whereYear('date', $year)->get();

        $purchasings = Purchasing::whereYear('date', $year)->get();

        $payrolls = Payroll::with('employee')->whereYear('pay_date', $year)->get();

        $quarters = [
            'Q1' => collect(),
            'Q2' => collect(),
            'Q3' => collect(),
            'Q4' => collect(),
        ];

        $summary = [
            'Q1' => ['income' => 0, 'expense' => 0, 'payroll' => 0, 'balance' => 0],
            'Q2' => ['income' => 0, 'expense' => 0, 'payroll' => 0, 'balance' => 0],
            'Q3' => ['income' => 0, 'expense' => 0, 'payroll' => 0, 'balance' => 0],
            'Q4' => ['income' => 0, 'expense' => 0, 'payroll' => 0, 'balance' => 0],
        ];

        // Masukkan data income
        foreach ($incomes as $inc) {
            $quarter = 'Q'.\Carbon\Carbon::parse($inc->date)->quarter;
            $quarters[$quarter]->push(
                (object) [
                    'date' => $inc->date,
                    'type' => 'income',
                    'description' => ($inc->deal->title ?? '-').($inc->deal->contact->company ? ' - '.$inc->deal->contact->company : ''),
                    'amount' => $inc->amount,
                ],
            );
            $summary[$quarter]['income'] += $inc->amount;
        }

        // Masukkan data purchasing
        foreach ($purchasings as $pur) {
            $quarter = 'Q'.\Carbon\Carbon::parse($pur->date)->quarter;
            $quarters[$quarter]->push(
                (object) [
                    'date' => $pur->date,
                    'type' => 'expense',
                    'description' => $pur->item_name ?? '-',
                    'amount' => $pur->total_price,
                ],
            );
            $summary[$quarter]['expense'] += $pur->total_price;
        }

        // Hitung total gaji per kuartal
        foreach ($payrolls as $p) {
            $quarter = 'Q'.\Carbon\Carbon::parse($p->pay_date)->quarter;
            $summary[$quarter]['payroll'] += $p->total;
        }

        // Hitung saldo per kuartal tanpa reference
        foreach (array_keys($summary) as $q) {
            $summary[$q]['balance'] = $summary[$q]['income'] - ($summary[$q]['expense'] + $summary[$q]['payroll']);
        }

        return view('accounting.reports.annual', compact('year', 'quarters', 'summary'));
    }
}

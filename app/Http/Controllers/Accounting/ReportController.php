<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Income;
use App\Models\Payroll;
use App\Models\Purchasing;
use App\Models\Transaction;
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
        $totalSalary = $payrolls->sum(function ($payroll) {
            return ($payroll->employee->salary ?? 0) + ($payroll->allowance ?? 0);
        });

        $totalExpense = $totalPurchasing + $totalSalary;
        $balance = $totalIncome - $totalExpense;

        return view('accounting.reports.index', compact('startDate', 'endDate', 'incomes', 'purchasings', 'payrolls', 'totalIncome', 'totalPurchasing', 'totalSalary', 'totalExpense', 'balance'));
    }

    public function annualReport(Request $request)
    {
        $year = $request->get('year', now()->year);

        // Ambil transaksi
        $transactions = Transaction::with(['income.deal.contact', 'purchasing'])
            ->whereYear('date', $year)
            ->get();

        // Ambil payroll
        $payrolls = Payroll::with('employee')
            ->whereYear('pay_date', $year)
            ->get();

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

        // Masukkan transaksi non-gaji
        foreach ($transactions as $t) {
            $quarter = 'Q'.ceil(\Carbon\Carbon::parse($t->date)->quarter);
            $quarters[$quarter]->push((object) [
                'date' => $t->date,
                'type' => $t->type,
                'description' => $t->type === 'income'
                    ? ($t->income->deal->contact->name ?? '-')
                    : ($t->purchasing->item_name ?? '-'),
                'amount' => $t->amount,
            ]);

            if ($t->type === 'income') {
                $summary[$quarter]['income'] += $t->amount;
            } else {
                $summary[$quarter]['expense'] += $t->amount;
            }
        }

        // Hitung total gaji per kuartal
        foreach ($payrolls as $p) {
            $quarter = 'Q'.ceil(\Carbon\Carbon::parse($p->pay_date)->quarter);
            $summary[$quarter]['payroll'] += $p->total;
        }

        // Hitung saldo per kuartal
        foreach ($summary as $q => &$s) {
            $s['balance'] = $s['income'] - ($s['expense'] + $s['payroll']);
        }

        return view('accounting.reports.annual', compact('year', 'quarters', 'summary'));
    }
}

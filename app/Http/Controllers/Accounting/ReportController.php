<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Income;
use App\Models\Purchasing;
use App\Models\Payroll;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Ambil filter tanggal dari request
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate   = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        // Ambil data berdasarkan rentang tanggal
        $incomes = Income::whereBetween('date', [$startDate, $endDate])->get();
        $purchasings = Purchasing::whereBetween('date', [$startDate, $endDate])->get();
        $payrolls = Payroll::whereBetween('pay_date', [$startDate, $endDate])->get();

        // Hitung total
        $totalIncome = $incomes->sum('amount');
        $totalPurchasing = $purchasings->sum('total_price');
        $totalSalary = $payrolls->sum(function($payroll) {
            return ($payroll->employee->salary ?? 0) + ($payroll->allowance ?? 0);
        });

        $totalExpense = $totalPurchasing + $totalSalary;
        $balance = $totalIncome - $totalExpense;

        return view('accounting.reports.index', compact(
            'startDate',
            'endDate',
            'incomes',
            'purchasings',
            'payrolls',
            'totalIncome',
            'totalPurchasing',
            'totalSalary',
            'totalExpense',
            'balance'
        ));
    }
}

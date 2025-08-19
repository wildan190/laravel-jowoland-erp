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

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        // Eager load
        $incomes = Income::with(['deal.contact'])
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        $purchasings = Purchasing::whereBetween('date', [$startDate, $endDate])->get();

        $payrolls = Payroll::with('employee')
            ->whereBetween('pay_date', [$startDate, $endDate])
            ->get();

        $loans = Loan::all(); // ambil semua pinjaman

        // Buat list cicilan per bulan sesuai periode
        $loanPayments = collect();

        foreach ($loans as $loan) {
            $monthlyPayment = ($loan->principal + ($loan->principal * $loan->interest_rate) / 100) / max(1, $loan->installments);
            $start = \Carbon\Carbon::parse($loan->start_date);
            $end = $start->copy()->addMonths($loan->installments - 1);

            $current = $start->copy();
            while ($current->lte($end)) {
                // hanya ambil yang masuk periode filter
                if ($current->between(\Carbon\Carbon::parse($startDate), \Carbon\Carbon::parse($endDate))) {
                    $loanPayments->push([
                        'vendor' => $loan->vendor,
                        'principal' => $loan->principal,
                        'interest_rate' => $loan->interest_rate,
                        'installments' => $loan->installments,
                        'due_date' => $current->copy(),
                        'monthly_payment' => $monthlyPayment,
                    ]);
                }
                $current->addMonth();
            }
        }

        // Hitung total
        $totalIncome = $incomes->sum('amount');
        $totalPurchasing = $purchasings->sum('total_price');

        $totalSalary = $payrolls->sum(function ($payroll) {
            $basicSalary = $payroll->employee->salary ?? 0;
            $allowance = $payroll->allowance ?? 0;
            $deduction = $payroll->deduction ?? 0;

            return $basicSalary + $allowance - $deduction;
        });

        $totalLoanPayment = $loanPayments->sum('monthly_payment');

        $totalExpense = $totalPurchasing + $totalLoanPayment + $totalSalary;
        $balance = $totalIncome - $totalExpense;

        return view('accounting.reports.index', compact('startDate', 'endDate', 'incomes', 'purchasings', 'payrolls', 'loanPayments', 'totalIncome', 'totalPurchasing', 'totalSalary', 'totalLoanPayment', 'totalExpense', 'balance'));
    }

    public function annualReport(Request $request)
    {
        $year = $request->get('year', now()->year);

        // ✅ Income diambil dari Invoice yang sudah dibayar (is_pending = 0)
        $invoices = Invoice::with('contact')->whereYear('due_date', $year)->where('is_pending', 0)->get();

        $purchasings = Purchasing::whereYear('date', $year)->get();
        $payrolls = Payroll::with('employee')->whereYear('pay_date', $year)->get();
        $loans = Loan::all(); // ambil semua pinjaman, bukan hanya yang jatuh tempo tahun ini

        $quarters = [
            'Q1' => collect(),
            'Q2' => collect(),
            'Q3' => collect(),
            'Q4' => collect(),
        ];

        $summary = [
            'Q1' => ['income' => 0, 'expense' => 0, 'payroll' => 0, 'loan' => 0, 'balance' => 0],
            'Q2' => ['income' => 0, 'expense' => 0, 'payroll' => 0, 'loan' => 0, 'balance' => 0],
            'Q3' => ['income' => 0, 'expense' => 0, 'payroll' => 0, 'loan' => 0, 'balance' => 0],
            'Q4' => ['income' => 0, 'expense' => 0, 'payroll' => 0, 'loan' => 0, 'balance' => 0],
        ];

        // === Income (Invoice Paid) ===
        foreach ($invoices as $inv) {
            $quarter = 'Q'.Carbon::parse($inv->due_date)->quarter;

            $desc = "Invoice #{$inv->invoice_number}";
            if ($inv->contact && $inv->contact->company) {
                $desc .= " - {$inv->contact->company}";
            }

            $quarters[$quarter]->push(
                (object) [
                    'date' => $inv->due_date,
                    'type' => 'income',
                    'description' => $desc,
                    'amount' => $inv->grand_total,
                ],
            );
            $summary[$quarter]['income'] += $inv->grand_total;
        }

        // === Purchasing ===
        foreach ($purchasings as $pur) {
            $quarter = 'Q'.Carbon::parse($pur->date)->quarter;
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

        // === Payroll (diringkas per kuartal) ===
        foreach ($payrolls as $p) {
            $quarter = 'Q'.Carbon::parse($p->pay_date)->quarter;
            $basicSalary = $p->employee->salary ?? 0;
            $allowance = $p->allowance ?? 0;
            $deduction = $p->deduction ?? 0;
            $total = $basicSalary + $allowance - $deduction;

            $summary[$quarter]['payroll'] += $total;
        }

        // Setelah loop payroll, masukkan 1 entri ringkasan per kuartal
        foreach (array_keys($summary) as $quarter) {
            if ($summary[$quarter]['payroll'] > 0) {
                $quarters[$quarter]->push(
                    (object) [
                        'date' => Carbon::create($year, $quarter === 'Q1' ? 3 : ($quarter === 'Q2' ? 6 : ($quarter === 'Q3' ? 9 : 12)), 30),
                        'type' => 'payroll',
                        'description' => 'Gaji Karyawan / Staff',
                        'amount' => $summary[$quarter]['payroll'],
                    ],
                );
            }
        }

        // === Loan: cicilan per bulan sesuai kuartal ===
        foreach ($loans as $loan) {
            $monthlyPayment = ($loan->principal + ($loan->principal * $loan->interest_rate) / 100) / max(1, $loan->installments);
            $start = Carbon::parse($loan->start_date);
            $end = $start->copy()->addMonths($loan->installments - 1);

            $current = $start->copy();
            while ($current->lte($end)) {
                if ($current->year == $year) {
                    $quarter = 'Q'.$current->quarter;
                    $quarters[$quarter]->push(
                        (object) [
                            'date' => $current->copy(),
                            'type' => 'loan',
                            'description' => "Hutang ke {$loan->vendor} - {$loan->description}",
                            'amount' => $monthlyPayment,
                        ],
                    );
                    $summary[$quarter]['loan'] += $monthlyPayment;
                }
                $current->addMonth();
            }
        }

        // === Balance per kuartal ===
        foreach (array_keys($summary) as $q) {
            $summary[$q]['balance'] = $summary[$q]['income'] - ($summary[$q]['expense'] + $summary[$q]['payroll'] + $summary[$q]['loan']);
        }

        return view('accounting.reports.annual', compact('year', 'quarters', 'summary'));
    }
}

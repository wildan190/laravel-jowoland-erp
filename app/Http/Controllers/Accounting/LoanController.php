<?php

namespace App\Http\Controllers\Accounting;

use App\Action\Accounting\LoanAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Accounting\StoreLoanRequest;
use App\Models\Loan;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function index(Request $request)
{
    $query = Loan::query();

    // Filter vendor/bank
    if ($request->filled('vendor')) {
        $query->where('vendor', 'like', '%' . $request->vendor . '%');
    }

    // Filter tanggal jatuh tempo mulai
    if ($request->filled('due_date_start')) {
        $query->where('due_date', '>=', $request->due_date_start);
    }

    // Filter tanggal jatuh tempo sampai
    if ($request->filled('due_date_end')) {
        $query->where('due_date', '<=', $request->due_date_end);
    }

    // Urutkan berdasarkan tanggal jatuh tempo
    $loans = $query->orderBy('due_date', 'asc')->get();

    return view('accounting.loans.index', compact('loans'));
}


    public function create()
    {
        return view('accounting.loans.create');
    }

    public function store(StoreLoanRequest $request, LoanAction $action)
    {
        $action->create($request->validated());

        return redirect()->route('accounting.loans.index')->with('success', 'Data hutang berhasil ditambahkan.');
    }

    public function edit(Loan $loan)
    {
        return view('accounting.loans.edit', compact('loan'));
    }

    public function update(StoreLoanRequest $request, Loan $loan, LoanAction $action)
    {
        $action->update($loan, $request->validated());

        return redirect()->route('accounting.loans.index')->with('success', 'Data hutang berhasil diperbarui.');
    }

    public function destroy(Loan $loan, LoanAction $action)
    {
        $action->delete($loan);

        return redirect()->route('accounting.loans.index')->with('success', 'Data hutang berhasil dihapus.');
    }
}

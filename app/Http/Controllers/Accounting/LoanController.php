<?php

namespace App\Http\Controllers\Accounting;

use App\Action\Accounting\LoanAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Accounting\StoreLoanRequest;
use App\Models\Loan;

class LoanController extends Controller
{
    public function index()
    {
        $loans = Loan::orderBy('due_date', 'asc')->get();

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

<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Accounting\StoreTransactionRequest;
use App\Models\Transaction;
use App\Models\Income;
use App\Models\Purchasing;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['income.deal.contact', 'purchasing'])
            ->latest()
            ->get();

        $totalIncome = $transactions->where('type', 'income')->sum('amount');
        $totalExpense = $transactions->where('type', 'expense')->sum('amount');
        $balance = $totalIncome - $totalExpense;

        return view('accounting.transactions.index', compact('transactions', 'totalIncome', 'totalExpense', 'balance'));
    }

    public function create()
    {
        $incomes = Income::with('deal.contact')->get();
        $purchasings = Purchasing::all();
        return view('accounting.transactions.create', compact('incomes', 'purchasings'));
    }

    public function store(StoreTransactionRequest $request)
    {
        $data = $request->validated();
        if ($request->type === 'income' && $request->income_id) {
            $amount = Income::findOrFail($request->income_id)->amount;
        } elseif ($request->type === 'expense' && $request->purchasing_id) {
            $amount = Purchasing::findOrFail($request->purchasing_id)->total_price;
        }
        Transaction::create([
            'date' => $request->date,
            'type' => $request->type,
            'income_id' => $request->income_id,
            'purchasing_id' => $request->purchasing_id,
            'amount' => $amount,
        ]);

        return redirect()->route('accounting.transactions.index')->with('success', 'Transaksi berhasil ditambahkan.');
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
        return back()->with('success', 'Transaksi berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Accounting\StoreIncomeRequest;
use App\Http\Requests\Accounting\UpdateIncomeRequest;
use App\Models\Deal;
use App\Models\Income;

class IncomeController extends Controller
{
    /**
     * List semua pemasukan.
     */
    public function index()
    {
        // Eager load relasi deal dan contact di dalamnya
        $incomes = Income::with(['deal.contact'])
            ->latest()
            ->get();

        return view('accounting.incomes.index', compact('incomes'));
    }

    /**
     * Form tambah pemasukan.
     */
    public function create()
    {
        $deals = Deal::with('contact')->orderBy('id', 'desc')->get();

        return view('accounting.incomes.create', compact('deals'));
    }

    /**
     * Simpan pemasukan baru.
     */
    public function store(StoreIncomeRequest $request)
    {
        $deal = Deal::with('contact')->findOrFail($request->deal_id);

        Income::create([
            'deal_id' => $deal->id,
            'amount' => $deal->value, // ambil langsung dari deal
            'date' => $request->date,
            'description' => $request->description ?? "Pembayaran dari {$deal->contact->name}",
        ]);

        return redirect()->route('accounting.incomes.index')->with('success', 'Pemasukan berhasil ditambahkan.');
    }

    /**
     * Form edit pemasukan.
     */
    public function edit(Income $income)
    {
        $income->load('deal.contact');
        $deals = Deal::with('contact')->orderBy('id', 'desc')->get();

        return view('accounting.incomes.edit', compact('income', 'deals'));
    }

    /**
     * Update pemasukan.
     */
    public function update(UpdateIncomeRequest $request, Income $income)
    {
        $deal = Deal::with('contact')->findOrFail($request->deal_id);

        $income->update([
            'deal_id' => $deal->id,
            'amount' => $deal->value, // tetap otomatis
            'date' => $request->date,
            'description' => $request->description ?? "Pembayaran dari {$deal->contact->name}",
        ]);

        return redirect()->route('accounting.incomes.index')->with('success', 'Pemasukan berhasil diperbarui.');
    }

    /**
     * Hapus pemasukan.
     */
    public function destroy(Income $income)
    {
        $income->delete();

        return back()->with('success', 'Pemasukan berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Accounting\StoreIncomeRequest;
use App\Http\Requests\Accounting\UpdateIncomeRequest;
use App\Models\Income;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    /**
     * List semua pemasukan dengan filter tanggal.
     */
    public function index(Request $request)
    {
        $query = Income::with(['deal.contact']);

        // Filter berdasarkan tanggal mulai
        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }

        // Filter berdasarkan tanggal akhir
        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        $incomes = $query->latest()->paginate(10)->appends($request->query());

        return view('accounting.incomes.index', compact('incomes'));
    }

    /**
     * Form tambah pemasukan.
     */
    public function create()
    {
        $invoices = \App\Models\Invoice::with('project.contact')
            ->where('is_pending', false)
            ->orderBy('id', 'desc')
            ->get();

        return view('accounting.incomes.create', compact('invoices'));
    }

    public function store(StoreIncomeRequest $request)
    {
        $invoice = \App\Models\Invoice::with('project.contact')
            ->where('is_pending', false)
            ->findOrFail($request->invoice_id);

        Income::create([
            'invoice_id' => $invoice->id,
            'contact_id' => $invoice->project->contact->id,
            'amount' => $invoice->grand_total,
            'date' => $request->date,
            'description' => $request->description ?? "Pembayaran dari {$invoice->project->contact->name}",
        ]);

        return redirect()->route('accounting.incomes.index')->with('success', 'Pemasukan berhasil ditambahkan.');
    }

    /**
     * Form edit pemasukan.
     */
    public function edit(Income $income)
    {
        $invoices = \App\Models\Invoice::with('project.contact')
            ->where('is_pending', false)
            ->orderBy('id', 'desc')
            ->get();

        return view('accounting.incomes.edit', compact('income', 'invoices'));
    }

    /**
     * Update pemasukan.
     */
    public function update(UpdateIncomeRequest $request, Income $income)
    {
        $invoice = \App\Models\Invoice::with('project.contact')
            ->where('is_pending', false)
            ->findOrFail($request->invoice_id);

        $income->update([
            'invoice_id' => $invoice->id,
            'contact_id' => $invoice->project->contact->id,
            'amount' => $invoice->grand_total,
            'date' => $request->date,
            'description' => $request->description ?? "Pembayaran dari {$invoice->project->contact->name}",
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

<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with('project.contact');

        // Filter nomor invoice
        if ($request->filled('invoice_number')) {
            $query->where('invoice_number', 'like', '%'.$request->invoice_number.'%');
        }

        // Filter nama proyek / client
        if ($request->filled('project_name')) {
            $query->whereHas('project', function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->project_name.'%')->orWhereHas('contact', function ($q2) use ($request) {
                    $q2->where('name', 'like', '%'.$request->project_name.'%');
                });
            });
        }

        // Filter tanggal jatuh tempo
        if ($request->filled('due_date')) {
            $query->whereDate('due_date', $request->due_date);
        }

        // Ambil hasil akhir
        $invoices = $query->latest()->get();

        return view('accounting.invoices.index', compact('invoices'));
    }

    public function create()
    {
        $projects = \App\Models\Project::with(['contact', 'deal'])->get();

        return view('accounting.invoices.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'due_date' => 'required|date',
            'items' => 'nullable|array',
            'items.*.description' => 'nullable|string',
            'items.*.price' => 'nullable|numeric|min:0',
        ]);

        $invoice = \App\Action\Accounting\InvoiceAction::create($data);

        return redirect()->route('accounting.invoices.show', $invoice->id)->with('success', 'Invoice created successfully.');
    }

    public function show($id)
    {
        $invoice = Invoice::with(['project.contact', 'project.deal', 'items'])->findOrFail($id);

        return view('accounting.invoices.show', compact('invoice'));
    }

    public function updateStatus(Request $request, Invoice $invoice)
    {
        if ($invoice->is_pending === false) {
            return redirect()->back()->with('error', 'Invoice sudah Paid dan tidak bisa diubah lagi.');
        }

        $data = $request->validate([
            'is_pending' => 'required|boolean',
        ]);

        if ($data['is_pending'] == false) {
            $invoice->update(['is_pending' => false]);
        }

        return redirect()->back()->with('success', 'Status invoice berhasil diperbarui.');
    }

    public function exportPdf($id)
    {
        $invoice = Invoice::with(['project.contact', 'project.deal', 'items'])->findOrFail($id);

        $company = [
            'name' => 'PT Contoh Perusahaan',
            'address' => 'Jl. Raya No. 123, Jakarta',
            'phone' => '021-1234567',
            'email' => 'info@perusahaan.com',
            'npwp' => '01.234.567.8-999.000',
            'logo' => null,
            'stample' => null,
            'signature' => null,
        ];

        $logoPath = public_path('assets/img/logo.png');
        if (file_exists($logoPath)) {
            $company['logo'] = 'data:image/png;base64,'.base64_encode(file_get_contents($logoPath));
        }

        $stamplePath = public_path('assets/img/stample.png');
        if (file_exists($stamplePath)) {
            $company['stample'] = 'data:image/png;base64,'.base64_encode(file_get_contents($stamplePath));
        }

        $signaturePath = public_path('assets/img/signature.png');
        if (file_exists($signaturePath)) {
            $company['signature'] = 'data:image/png;base64,'.base64_encode(file_get_contents($signaturePath));
        }

        $subtotal = $invoice->project_amount + $invoice->items->sum('price');
        $ppn = $subtotal * 0.11;
        $grandTotal = $subtotal + $ppn;

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('accounting.invoices.pdf', [
            'invoice' => $invoice,
            'company' => $company,
            'subtotal' => $subtotal,
            'ppn' => $ppn,
            'grandTotal' => $grandTotal,
        ]);

        return $pdf->download("invoice-{$invoice->invoice_number}.pdf");
    }
}

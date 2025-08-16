<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Receipt;
use Illuminate\Http\Request;
use PDF; // pastikan sudah install barryvdh/laravel-dompdf

class ReceiptController extends Controller
{
    public function index()
    {
        $receipts = Receipt::with('invoice.project')->latest()->get();

        return view('accounting.receipts.index', compact('receipts'));
    }

    public function create()
    {
        $invoices = Invoice::with('project')->get(); // pilih invoice

        return view('accounting.receipts.create', compact('invoices'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'date' => 'required|date',
            'note' => 'nullable|string',
        ]);

        $invoice = Invoice::find($request->invoice_id);

        $receipt = Receipt::create([
            'invoice_id' => $invoice->id,
            'receipt_number' => $this->generateReceiptNumber(),
            'amount' => $invoice->grand_total,
            'date' => $request->date,
            'note' => $request->note,
        ]);

        return redirect()->route('accounting.receipts.index')->with('success', 'Kwitansi berhasil dibuat.');
    }

    public function show(Receipt $receipt)
    {
        $receipt->load('invoice.project');

        return view('accounting.receipts.show', compact('receipt'));
    }

    public function pdf(Receipt $receipt)
    {
        $receipt->load('invoice.project');
        $pdf = PDF::loadView('accounting.receipts.pdf', compact('receipt'));

        // Ganti karakter "/" menjadi "-" agar nama file valid
        $filename = str_replace('/', '-', $receipt->receipt_number).'.pdf';

        return $pdf->download($filename);
    }

    private function generateReceiptNumber()
    {
        $year = date('Y');
        $month = date('m');

        // Ambil kwitansi terakhir bulan ini
        $last = Receipt::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->orderBy('id', 'desc')
            ->first();

        $number = 1;
        if ($last) {
            // ambil nomor terakhir dari format KW/2025/08/001
            $parts = explode('/', $last->receipt_number);
            $number = (int) $parts[3] + 1;
        }

        return sprintf('KW/%s/%s/%03d', $year, $month, $number);
    }
}

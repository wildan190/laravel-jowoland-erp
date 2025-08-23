<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Invoice;
use App\Models\Receipt;
use Illuminate\Http\Request;
use PDF; // pastikan sudah install barryvdh/laravel-dompdf

class ReceiptController extends Controller
{
    public function index()
    {
        $contacts = Contact::all(); // Inisialisasi variabel $contacts
        $receipts = Receipt::with('invoice.project')->latest()->get();

        return view('accounting.receipts.index', compact('receipts', 'contacts'));
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

        // Konversi gambar ke base64
        $logo = base64_encode(file_get_contents(public_path('assets/img/logo.png')));
        $signature = base64_encode(file_get_contents(public_path('assets/img/signature.png')));
        $stample = base64_encode(file_get_contents(public_path('assets/img/stample.png')));

        // Fungsi terbilang
        function terbilang($angka)
        {
            $angka = abs($angka);
            $baca = ['', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh', 'sebelas'];
            $terbilang = '';

            if ($angka < 12) {
                $terbilang = ' '.$baca[$angka];
            } elseif ($angka < 20) {
                $terbilang = terbilang($angka - 10).' belas';
            } elseif ($angka < 100) {
                $terbilang = terbilang($angka / 10).' puluh'.terbilang($angka % 10);
            } elseif ($angka < 200) {
                $terbilang = ' seratus'.terbilang($angka - 100);
            } elseif ($angka < 1000) {
                $terbilang = terbilang($angka / 100).' ratus'.terbilang($angka % 100);
            } elseif ($angka < 2000) {
                $terbilang = ' seribu'.terbilang($angka - 1000);
            } elseif ($angka < 1000000) {
                $terbilang = terbilang($angka / 1000).' ribu'.terbilang($angka % 1000);
            } elseif ($angka < 1000000000) {
                $terbilang = terbilang($angka / 1000000).' juta'.terbilang($angka % 1000000);
            } elseif ($angka < 1000000000000) {
                $terbilang = terbilang($angka / 1000000000).' milyar'.terbilang(fmod($angka, 1000000000));
            } else {
                $terbilang = ' angka terlalu besar';
            }

            return $terbilang;
        }

        $pdf = PDF::loadView('accounting.receipts.pdf', [
            'receipt' => $receipt,
            'logo' => $logo,
            'signature' => $signature,
            'stample' => $stample,
            'terbilang' => terbilang($receipt->amount), // Menambahkan terbilang ke view
        ]);

        $filename = str_replace('/', '-', $receipt->receipt_number).'.pdf';

        return $pdf->download($filename);
    }

    private function generateReceiptNumber()
    {
        $year = date('Y');
        $month = date('m');

        // Ambil kwitansi terakhir bulan ini
        $last = Receipt::whereYear('date', $year)->whereMonth('date', $month)->orderBy('id', 'desc')->first();

        $number = 1;
        if ($last) {
            // ambil nomor terakhir dari format KW/2025/08/001
            $parts = explode('/', $last->receipt_number);
            $number = (int) $parts[3] + 1;
        }

        return sprintf('KW/%s/%s/%03d', $year, $month, $number);
    }
}

<?php

namespace App\Http\Controllers\CRM;

use App\Action\CRM\Quotation\GenerateQuotationNumber;
use App\Http\Controllers\Controller;
use App\Http\Requests\CRM\StoreQuotationRequest;
use App\Models\Contact;
use App\Models\Quotation;
use Illuminate\Http\Request;
use PDF;

class QuotationController extends Controller
{
    public function index()
    {
        $quotations = Quotation::with('contact')->latest()->get();

        return view('crm.quotations.index', compact('quotations'));
    }

    public function create()
    {
        $contacts = Contact::all();

        // Default kategori mini_crane
        $quotationNumber = GenerateQuotationNumber::handle('PHM');
        $quotationDate = now()->format('Y-m-d');

        return view('crm.quotations.create', compact('contacts', 'quotationNumber', 'quotationDate'));
    }

    public function store(StoreQuotationRequest $request)
    {
        // Map kategori ke kode
        $categoryMap = [
            'hydraulic' => 'PHR',
            'mini_crane' => 'PHM',
            'strauss' => 'PHS',
        ];

        $categoryCode = $categoryMap[$request->category] ?? 'PHM';

        // Hitung subtotal dari items
        $subtotal = collect($request->items)->sum(function ($item) {
            return ($item['qty'] ?? 0) * ($item['price'] ?? 0);
        });

        $ppn = $subtotal * 0.11;
        $total = $subtotal + $ppn;

        // Generate nomor quotation dengan kategori
        $quotationNumber = GenerateQuotationNumber::handle($categoryCode);

        // Simpan quotation utama
        $quotation = Quotation::create([
            'contact_id' => $request->contact_id,
            'category' => $request->category,
            'quotation_number' => $quotationNumber,
            'quotation_date' => now(),
            'subtotal' => $subtotal,
            'ppn' => $ppn,
            'total' => $total,
        ]);

        // Simpan item–item detail
        foreach ($request->items as $item) {
            if (!empty($item['item']) && ($item['qty'] ?? 0) > 0) {
                $quotation->items()->create([
                    'item' => $item['item'],
                    'description' => $item['description'] ?? '',
                    'qty' => $item['qty'] ?? 1,
                    'price' => $item['price'] ?? 0,
                    'total' => ($item['qty'] ?? 0) * ($item['price'] ?? 0),
                ]);
            }
        }

        return redirect()
            ->route('crm.quotations.index')
            ->with('success', "Quotation {$quotationNumber} created successfully");
    }

    public function destroy(Quotation $quotation)
    {
        $quotation->items()->delete(); // hapus detail dulu
        $quotation->delete();

        return redirect()
            ->route('crm.quotations.index')
            ->with('success', "Quotation {$quotation->quotation_number} berhasil dihapus");
    }

    public function exportPdf(Quotation $quotation)
    {
        $quotation->load('contact', 'items');

        // Hitung subtotal dari items
        $subtotal = $quotation->items->sum(function ($item) {
            return ($item->qty ?? 0) * ($item->price ?? 0);
        });

        $ppn = $subtotal * 0.11;
        $grandTotal = $subtotal + $ppn;

        // Encode logo ke base64
        $logoPath = public_path('assets/img/logo.png');
        $logoBase64 = file_exists($logoPath) ? 'data:image/' . pathinfo($logoPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($logoPath)) : null;

        // Generate PDF
        $pdf = PDF::loadView('crm.quotations.pdf', [
            'quotation' => $quotation,
            'logoBase64' => $logoBase64,
            'subtotal' => $subtotal,
            'ppn' => $ppn,
            'grandTotal' => $grandTotal,
        ])->setPaper('a4', 'portrait');

        // Bersihkan nama file (hilangkan "/" atau "\")
        $safeFileName = str_replace(['/', '\\'], '-', $quotation->quotation_number);

        return $pdf->download("Quotation-{$safeFileName}.pdf");
    }
}

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
    public function index(Request $request)
    {
        $query = Quotation::with('contact');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('quotation_number', 'like', "%{$search}%")
                  ->orWhereHas('contact', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($category = $request->get('category')) {
            $query->where('category', $category);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('quotation_date', [$request->start_date, $request->end_date]);
        }

        $quotations = $query->latest()->paginate(10)->appends($request->query());

        return view('crm.quotations.index', compact('quotations'));
    }

    public function create()
    {
        $contacts = Contact::all();
        $quotationNumber = GenerateQuotationNumber::handle('PHM');
        $quotationDate = now()->format('Y-m-d');

        return view('crm.quotations.create', compact('contacts', 'quotationNumber', 'quotationDate'));
    }

    public function store(StoreQuotationRequest $request)
    {
        $categoryMap = [
            'hydraulic' => 'PHR',
            'mini_crane' => 'PHM',
            'strauss' => 'PHS',
        ];

        $categoryCode = $categoryMap[$request->category] ?? 'PHM';

        // Hitung subtotal dari items
        $subtotal = collect($request->items)->sum(function ($item) {
            $qty = $item['qty'] ?? 0;
            return $qty == 0 ? ($item['total'] ?? 0) : ($qty * ($item['price'] ?? 0));
        });

        // Tentukan PPN berdasarkan include_ppn
        $ppn = $request->include_ppn ? ($subtotal * 0.11) : 0;
        $total = $subtotal + $ppn;

        // Generate nomor quotation
        $quotationNumber = GenerateQuotationNumber::handle($categoryCode);

        // Simpan quotation
        $quotation = Quotation::create([
            'contact_id' => $request->contact_id,
            'category' => $request->category,
            'quotation_number' => $quotationNumber,
            'quotation_date' => now(),
            'subtotal' => $subtotal,
            'ppn' => $ppn,
            'total' => $total,
            'include_ppn' => $request->include_ppn ?? false,
        ]);

        // Simpan item
        foreach ($request->items as $item) {
            if (!empty($item['item']) && isset($item['qty'])) {
                $qty = $item['qty'] ?? 0;
                $quotation->items()->create([
                    'item' => $item['item'],
                    'description' => $item['description'] ?? '',
                    'qty' => $qty,
                    'satuan' => $item['satuan'] ?? '',
                    'price' => $item['price'] ?? 0,
                    'total' => $qty == 0 ? ($item['total'] ?? 0) : ($qty * ($item['price'] ?? 0)),
                    'terms' => $item['terms'] ?? '',
                ]);
            }
        }

        return redirect()
            ->route('crm.quotations.index')
            ->with('success', "Quotation {$quotationNumber} created successfully");
    }

    public function edit(Quotation $quotation)
    {
        $contacts = Contact::all();

        return view('crm.quotations.edit', compact('quotation', 'contacts'));
    }

    public function update(StoreQuotationRequest $request, Quotation $quotation)
    {
        $categoryMap = [
            'hydraulic' => 'PHR',
            'mini_crane' => 'PHM',
            'strauss' => 'PHS',
        ];
        $categoryCode = $categoryMap[$request->category] ?? 'PHM';

        // Hitung subtotal
        $subtotal = collect($request->items)->sum(function ($item) {
            $qty = $item['qty'] ?? 0;
            return $qty == 0 ? ($item['total'] ?? 0) : ($qty * ($item['price'] ?? 0));
        });

        // Tentukan PPN berdasarkan include_ppn
        $ppn = $request->include_ppn ? ($subtotal * 0.11) : 0;
        $total = $subtotal + $ppn;

        // Update quotation
        $quotation->update([
            'contact_id' => $request->contact_id,
            'category' => $request->category,
            'subtotal' => $subtotal,
            'ppn' => $ppn,
            'total' => $total,
            'include_ppn' => $request->include_ppn ?? false,
        ]);

        // Hapus item lama dan simpan ulang
        $quotation->items()->delete();

        foreach ($request->items as $item) {
            if (!empty($item['item']) && isset($item['qty'])) {
                $qty = $item['qty'] ?? 0;
                $quotation->items()->create([
                    'item' => $item['item'],
                    'description' => $item['description'] ?? '',
                    'qty' => $qty,
                    'satuan' => $item['satuan'] ?? '',
                    'price' => $item['price'] ?? 0,
                    'total' => $qty == 0 ? ($item['total'] ?? 0) : ($qty * ($item['price'] ?? 0)),
                    'terms' => $item['terms'] ?? '',
                ]);
            }
        }

        return redirect()
            ->route('crm.quotations.index')
            ->with('success', "Quotation {$quotation->quotation_number} updated successfully");
    }

    public function destroy(Quotation $quotation)
    {
        $quotation->items()->delete();
        $quotation->delete();

        return redirect()
            ->route('crm.quotations.index')
            ->with('success', "Quotation {$quotation->quotation_number} berhasil dihapus");
    }

    public function exportPdf(Quotation $quotation)
    {
        $quotation->load('contact', 'items');

        // Gunakan data dari database
        $subtotal = $quotation->subtotal;
        $ppn = $quotation->include_ppn ? $quotation->ppn : 0;
        $grandTotal = $subtotal + $ppn;

        // Encode logo ke base64
        $logoPath = public_path('assets/img/logo.png');
        $logoBase64 = file_exists($logoPath) ? 'data:image/'.pathinfo($logoPath, PATHINFO_EXTENSION).';base64,'.base64_encode(file_get_contents($logoPath)) : null;

        // Encode stample dan signature ke base64
        $stamplePath = public_path('assets/img/stample.png');
        $stampleBase64 = file_exists($stamplePath) ? 'data:image/'.pathinfo($stamplePath, PATHINFO_EXTENSION).';base64,'.base64_encode(file_get_contents($stamplePath)) : null;

        $signaturePath = public_path('assets/img/signature.png');
        $signatureBase64 = file_exists($signaturePath) ? 'data:image/'.pathinfo($signaturePath, PATHINFO_EXTENSION).';base64,'.base64_encode(file_get_contents($signaturePath)) : null;

        // Generate PDF
        $pdf = PDF::loadView('crm.quotations.pdf', [
            'quotation' => $quotation,
            'logoBase64' => $logoBase64,
            'stampleBase64' => $stampleBase64,
            'signatureBase64' => $signatureBase64,
            'subtotal' => $subtotal,
            'ppn' => $ppn,
            'grandTotal' => $grandTotal,
        ])->setPaper('a4', 'portrait');

        $safeFileName = str_replace(['/', '\\'], '-', $quotation->quotation_number);

        return $pdf->download("Quotation-{$safeFileName}.pdf");
    }
}
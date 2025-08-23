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

        // 🔍 Search umum (quotation number, contact name)
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('quotation_number', 'like', "%{$search}%")->orWhereHas('contact', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%");
                });
            });
        }

        // 📌 Filter kategori
        if ($category = $request->get('category')) {
            $query->where('category', $category);
        }

        // 📌 Filter by date range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('quotation_date', [$request->start_date, $request->end_date]);
        }

        // Pagination
        $quotations = $query->latest()->paginate(10)->appends($request->query());

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
            if (! empty($item['item']) && ($item['qty'] ?? 0) > 0) {
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

    public function edit(Quotation $quotation)
    {
        $contacts = Contact::all();

        return view('crm.quotations.edit', compact('quotation', 'contacts'));
    }

    public function update(StoreQuotationRequest $request, Quotation $quotation)
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

        // Update quotation utama
        $quotation->update([
            'contact_id' => $request->contact_id,
            'category' => $request->category,
            'subtotal' => $subtotal,
            'ppn' => $ppn,
            'total' => $total,
            // nomor quotation tetap tidak berubah, jika ingin regenerasi bisa uncomment:
            // 'quotation_number' => GenerateQuotationNumber::handle($categoryCode),
            // 'quotation_date' => now(),
        ]);

        // Hapus item lama dan buat ulang
        $quotation->items()->delete();

        foreach ($request->items as $item) {
            if (! empty($item['item']) && ($item['qty'] ?? 0) > 0) {
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
            ->with('success', "Quotation {$quotation->quotation_number} updated successfully");
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

        // Bersihkan nama file (hilangkan "/" atau "\")
        $safeFileName = str_replace(['/', '\\'], '-', $quotation->quotation_number);

        return $pdf->download("Quotation-{$safeFileName}.pdf");
    }
}

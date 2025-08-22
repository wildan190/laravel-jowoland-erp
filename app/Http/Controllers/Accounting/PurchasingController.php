<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Accounting\StorePurchasingRequest;
use App\Models\Purchasing;
use Illuminate\Http\Request;

class PurchasingController extends Controller
{
    public function index(Request $request)
    {
        $query = Purchasing::query();

        // 🔍 Filter nama barang
        if ($name = $request->get('name')) {
            $query->where('item_name', 'like', "%{$name}%");
        }

        // 🔍 Filter harga (unit_price)
        if ($minPrice = $request->get('min_price')) {
            $query->where('unit_price', '>=', $minPrice);
        }
        if ($maxPrice = $request->get('max_price')) {
            $query->where('unit_price', '<=', $maxPrice);
        }

        // 🔍 Filter tanggal
        if ($startDate = $request->get('start_date')) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate = $request->get('end_date')) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        // Urut terbaru & paginasi
        $purchasings = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('accounting.purchasings.index', compact('purchasings'));
    }

    public function create()
    {
        return view('accounting.purchasings.create');
    }

    public function store(StorePurchasingRequest $request)
    {
        $data = $request->validated();
        $data['total_price'] = $data['unit_price'] * $data['quantity'];
        Purchasing::create($data);

        return redirect()->route('accounting.purchasings.index')->with('success', 'Pembelian berhasil ditambahkan.');
    }

    public function edit(Purchasing $purchasing)
    {
        return view('accounting.purchasings.edit', compact('purchasing'));
    }

    public function update(StorePurchasingRequest $request, Purchasing $purchasing)
    {
        $data = $request->validated();
        $data['total_price'] = $data['unit_price'] * $data['quantity'];
        $purchasing->update($data);

        return redirect()->route('accounting.purchasings.index')->with('success', 'Pembelian berhasil diperbarui.');
    }

    public function destroy(Purchasing $purchasing)
    {
        $purchasing->delete();

        return back()->with('success', 'Pembelian berhasil dihapus.');
    }
}

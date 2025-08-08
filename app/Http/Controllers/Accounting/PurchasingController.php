<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Accounting\StorePurchasingRequest;
use App\Models\Purchasing;

class PurchasingController extends Controller
{
    public function index()
    {
        $purchasings = Purchasing::latest()->get();
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

<?php

namespace App\Http\Requests\Accounting;

use Illuminate\Foundation\Http\FormRequest;

class StorePurchasingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // bisa diganti logic otorisasi sesuai kebutuhan
    }

    public function rules(): array
    {
        return [
            'item_name' => 'required|string|max:255',
            'unit_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'date' => 'required|date',
        ];
    }

    public function messages(): array
    {
        return [
            'item_name.required' => 'Nama barang wajib diisi.',
            'unit_price.required' => 'Harga satuan wajib diisi.',
            'quantity.required' => 'Jumlah barang wajib diisi.',
            'date.required' => 'Tanggal pembelian wajib diisi.',
        ];
    }
}

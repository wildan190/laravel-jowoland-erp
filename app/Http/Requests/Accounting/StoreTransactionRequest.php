<?php

namespace App\Http\Requests\Accounting;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // bisa diganti sesuai policy/permission
    }

    public function rules(): array
    {
        return [
            'type'          => 'required|in:income,expense',
            'income_id'     => 'nullable|exists:incomes,id',
            'purchasing_id' => 'nullable|exists:purchasings,id',
            'amount'        => 'required|numeric|min:0',
            'description'   => 'nullable|string|max:255',
            'date'          => 'required|date'
        ];
    }

    public function messages(): array
    {
        return [
            'type.required'  => 'Jenis transaksi wajib diisi.',
            'type.in'        => 'Jenis transaksi harus income atau expense.',
            'amount.required'=> 'Nominal transaksi wajib diisi.',
            'date.required'  => 'Tanggal transaksi wajib diisi.',
        ];
    }
}

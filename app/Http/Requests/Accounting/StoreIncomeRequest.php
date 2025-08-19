<?php

namespace App\Http\Requests\Accounting;

use Illuminate\Foundation\Http\FormRequest;

class StoreIncomeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'invoice_id' => 'required|exists:invoices,id',
            'contact_id' => 'required|exists:contacts,id',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
        ];
    }
}

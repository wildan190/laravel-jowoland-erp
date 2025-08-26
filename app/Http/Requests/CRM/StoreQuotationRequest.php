<?php

namespace App\Http\Requests\CRM;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuotationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'contact_id' => 'required|exists:contacts,id',
            'items' => 'required|array|min:1',
            'items.*.item' => 'required|string',
            'items.*.description' => 'nullable|string',
            'items.*.qty' => 'required|integer|min:0',
            'items.*.satuan' => 'required|string',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.total' => 'required_if:items.*.qty,0|numeric|min:0',
            'items.*.terms' => 'nullable|string',
        ];
    }
}

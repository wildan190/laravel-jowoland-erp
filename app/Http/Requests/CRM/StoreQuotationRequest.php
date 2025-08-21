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
            'items.*.qty' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ];
    }
}

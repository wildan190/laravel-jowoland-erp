<?php

namespace App\Http\Requests\Accounting;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // bisa diganti dengan policy
    }

    public function rules(): array
    {
        return [
            'project_id' => 'required|exists:projects,id',
            'due_date' => 'required|date',
            'items' => 'nullable|array',
            'items.*.description' => 'required_with:items|string|max:255',
            'items.*.price' => 'required_with:items|numeric|min:0',
        ];
    }
}

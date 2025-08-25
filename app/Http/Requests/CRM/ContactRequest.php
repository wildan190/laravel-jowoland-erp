<?php

namespace App\Http\Requests\CRM;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:contacts,email,'.$this->contact?->id,
            'phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

<?php

namespace App\Http\Requests\Accounting;

use Illuminate\Foundation\Http\FormRequest;

class StoreLoanRequest extends FormRequest
{
    public function rules()
    {
        return [
            'vendor' => 'required|string|max:255',
            'principal' => 'required|numeric|min:0',
            'interest_rate' => 'required|numeric|min:0',
            'installments' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:start_date',
            'description' => 'nullable|string',
        ];
    }
}

class UpdateLoanRequest extends StoreLoanRequest {}

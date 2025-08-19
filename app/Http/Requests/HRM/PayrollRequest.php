<?php

namespace App\Http\Requests\HRM;

use Illuminate\Foundation\Http\FormRequest;

class PayrollRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_ids' => 'required|array|min:1',
            'employee_ids.*' => 'exists:employees,id', // pastikan id valid

            'basic_salaries' => 'required|array',
            'basic_salaries.*' => 'required|numeric|min:0',

            'allowances' => 'nullable|array',
            'allowances.*' => 'nullable|numeric|min:0',

            'notes' => 'nullable|array',
            'notes.*' => 'nullable|string|max:1000',

            'pay_date' => 'required|date',
        ];
    }
}

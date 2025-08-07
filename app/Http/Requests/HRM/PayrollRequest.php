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
            'employee_id' => 'required|exists:employees,id',
            'pay_date' => 'required|date',
            'allowance' => 'nullable|numeric|min:0',
            'basic_salary' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ];
    }
}

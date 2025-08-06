<?php

namespace App\Http\Requests\ProjectManagement;

use Illuminate\Foundation\Http\FormRequest;

class ProjectProgressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'stage' => 'required|string|max:255',
            'percentage' => 'required|integer|min:0|max:100',
            'note' => 'nullable|string',
        ];
    }
}

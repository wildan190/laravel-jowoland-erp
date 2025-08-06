<?php

namespace App\Http\Requests\CRM;

use Illuminate\Foundation\Http\FormRequest;

class DealStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'contact_id' => 'required|exists:contacts,id',
            'pipeline_stage_id' => 'required|exists:pipeline_stages,id',
            'value' => 'nullable|numeric',
            'status' => 'required|in:open,won,lost',
            'notes' => 'nullable|string',
        ];
    }
}

<?php

namespace App\Http\Requests\Marketing;

use Illuminate\Foundation\Http\FormRequest;

class StoreSocialMediaPostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'platform' => 'required|string|max:255',
            'content' => 'nullable|string',
            'scheduled_at' => 'nullable|date',
            'prompt' => 'required|string', // For AI generation
        ];
    }
}

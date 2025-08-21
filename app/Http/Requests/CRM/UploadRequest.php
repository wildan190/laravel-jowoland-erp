<?php

namespace App\Http\Requests\CRM;

use Illuminate\Foundation\Http\FormRequest;

class UploadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // bisa diproteksi sesuai kebutuhan
    }

    public function rules(): array
    {
        return [
            'contact_id' => 'required|exists:contacts,id',
            'file' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ];
    }
}

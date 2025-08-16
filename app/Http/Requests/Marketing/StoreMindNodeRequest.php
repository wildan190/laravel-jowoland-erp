<?php

namespace App\Http\Requests\Marketing;

use Illuminate\Foundation\Http\FormRequest;

class StoreMindNodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mind_map_id' => 'required|exists:mind_maps,id',
            'parent_id' => 'nullable|exists:mind_nodes,id',
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
        ];
    }
}

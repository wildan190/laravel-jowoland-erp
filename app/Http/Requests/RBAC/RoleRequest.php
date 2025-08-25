<?php

namespace App\Http\Requests\RBAC;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|exists:permissions,name',
            'users' => 'nullable|array',
            'users.*' => 'integer|exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama role wajib diisi.',
            'name.min' => 'Nama role minimal 3 karakter.',
            'permissions.*.exists' => 'Permission tidak valid.',
            'users.*.exists' => 'User tidak valid.',
        ];
    }
}

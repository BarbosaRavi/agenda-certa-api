<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class RoleAssignPermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'uuid', 'exists:roles,id'],
            'permission_id' => ['required', 'uuid', 'exists:permissions,id'],
        ];
    }

    public function attributes(): array
    {
        return [
            'id' => 'ID do cargo',
            'permission_id' => 'ID da permissao',
        ];
    }

    #[Override]
    protected function prepareForValidation(): void
    {
        $this->merge([
            'id' => $this->route('id'),
        ]);
    }
}

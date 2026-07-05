<?php

namespace App\Http\Requests\Tenant;

use App\Rules\Tenant\ActiveTenant;
use Illuminate\Foundation\Http\FormRequest;
use Override;

class TenantUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'uuid', new ActiveTenant()],
            'name' => ['required', 'string', 'max:50', 'min:3'],
            'email' => ['required', 'email', 'unique:users'],
        ];
    }

    public function attributes(): array
    {
        return [
            'id' => 'ID do tenant',
        ];
    }

    #[Override]
    public function prepareForValidation()
    {
        $this->merge([
            'id' => $this->route('id'),
        ]);
    }
}
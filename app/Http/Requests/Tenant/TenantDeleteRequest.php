<?php

namespace App\Http\Requests\Tenant;

use App\Rules\Tenant\ActiveTenant;
use Illuminate\Foundation\Http\FormRequest;
use Override;

class TenantDeleteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'uuid', new ActiveTenant()],
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
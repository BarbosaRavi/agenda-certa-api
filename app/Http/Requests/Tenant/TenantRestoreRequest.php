<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class TenantRestoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'uuid', 'exists:tenant,id'],
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
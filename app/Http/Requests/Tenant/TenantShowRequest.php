<?php

namespace App\Http\Requests\Tenant;

use App\Models\Tenant;
use App\Rules\IsActiveUserRule;
use Illuminate\Foundation\Http\FormRequest;
use Override;

class TenantShowRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'uuid', new IsActiveUserRule(Tenant::class)],
        ];
    }

    public function attributes(): array
    {
        return [
            'id' => 'ID do Inquilino',
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
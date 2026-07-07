<?php

namespace App\Http\Requests\Tenant;

use App\Models\Tenant;
use App\Rules\IsActiveUserRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\PhoneValidationRule;
use Illuminate\Validation\Rule;
use Override;

class TenantUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $tenant = Tenant::find($this->route('id'));
        $userId = $tenant?->user_id;

        return [
            'id' => ['required', 'uuid', new IsActiveUserRule(Tenant::class)],
            'name' => ['required', 'string', 'max:50', 'min:3'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($userId)],
            'phone' => ['required', Rule::unique('users', 'phone')->ignore($userId), new PhoneValidationRule()],
        ];
    }

    public function attributes(): array
    {
        return [
            'id' => 'ID do Inquilino',
            'name' => 'Nome',
            'email' => 'Email',
            'phone' => 'Telefone',
        ];
    }

    #[Override]
    public function prepareForValidation()
    {
        $this->merge([
            'id' => $this->route('id'),
        ]);

        if ($this->has('phone')) {
            $this->merge([
                'phone' => preg_replace('/\D+/', '', (string) $this->input('phone')),
            ]);
        }
    }
}

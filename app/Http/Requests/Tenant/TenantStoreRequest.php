<?php

namespace App\Http\Requests\Tenant;

use App\Rules\PhoneValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Override;

class TenantStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:50', 'min:3'],
            'email' => ['required', 'email', 'unique:users'],
            'phone' => ['required', 'unique:users,phone', new PhoneValidationRule()],
            'password' => ['required', 'string', 'min:8', 'max:50', 'confirmed'],
            'profile_picture' => ['sometimes', 'nullable', 'image', 'file', 'max:2048'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Nome',
            'email' => 'Email',
            'phone' => 'Telefone',
            'password' => 'Senha',
            'profile_picture' => 'Foto de perfil',
        ];
    }

    #[Override]
    protected function prepareForValidation(): void
    {
        if ($this->has('phone')) {
            $this->merge([
                'phone' => preg_replace('/\D+/', '', (string) $this->input('phone')),
            ]);
        }
    }
}

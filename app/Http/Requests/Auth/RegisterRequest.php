<?php

namespace App\Http\Requests\Auth;

use App\Enums\UserTypeEnum;
use App\Rules\PhoneValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Override;

class RegisterRequest extends FormRequest
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
            'phone' => ['sometimes', 'nullable', 'unique:users,phone', new PhoneValidationRule()],
            'password' => ['required', 'string', 'min:8', 'max:50', 'confirmed'],
            'user_type' => ['required', Rule::in([UserTypeEnum::CLIENT->value, UserTypeEnum::TENANT->value])],
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
            'user_type' => 'Tipo de usuario',
            'profile_picture' => 'Foto de perfil',
        ];
    }

    #[Override]
    protected function prepareForValidation(): void
    {
        if ($this->has('phone')) {
            $phone = preg_replace('/\D+/', '', (string) $this->input('phone'));

            $this->merge([
                'phone' => $phone !== '' ? $phone : null,
            ]);
        }
    }
}

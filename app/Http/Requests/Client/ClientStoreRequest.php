<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class ClientStoreRequest extends FormRequest
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
            'phone' => ['required', 'string', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'max:50', 'confirmed'],
            'profile_picture' => ['sometimes', 'nullable', 'image', 'file', 'size:2048'],
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
}
<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UploadProfilePictureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'profile_picture' => ['required', 'image', 'max:2048'],
        ];
    }

    public function attributes(): array
    {
        return [
            'profile_picture' => 'Foto de perfil',
        ];
    }
}
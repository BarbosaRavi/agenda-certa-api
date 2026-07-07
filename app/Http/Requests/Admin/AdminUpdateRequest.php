<?php

namespace App\Http\Requests\Admin;

use App\Models\Admin;
use App\Rules\IsActiveUserRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\PhoneValidationRule;
use Illuminate\Validation\Rule;
use Override;

class AdminUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $admin = Admin::find($this->route('id'));
        $userId = $admin?->user_id;

        return [
            'id' => ['required', 'uuid', new IsActiveUserRule(Admin::class)],
            'name' => ['required', 'string', 'max:50', 'min:3'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($userId)],
            'phone' => ['required', Rule::unique('users', 'phone')->ignore($userId), new PhoneValidationRule()],
        ];
    }

    public function attributes(): array
    {
        return [
            'id' => 'ID do Administrador',
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

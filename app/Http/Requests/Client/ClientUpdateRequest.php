<?php

namespace App\Http\Requests\Client;

use App\Models\Client;
use App\Rules\IsActiveUserRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\PhoneValidationRule;
use Illuminate\Validation\Rule;
use Override;

class ClientUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $client = Client::find($this->route('id'));
        $userId = $client?->user_id;

        return [
            'id' => ['required', 'uuid', new IsActiveUserRule(Client::class)],
            'name' => ['required', 'string', 'max:50', 'min:3'],
            'email' => ['required', 'email', 'unique:users'],
            'phone' => ['required', Rule::unique('users', 'phone')->ignore($userId), new PhoneValidationRule()],
        ];
    }

    public function attributes(): array
    {
        return [
            'id' => 'ID do Cliente',
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
    }
}
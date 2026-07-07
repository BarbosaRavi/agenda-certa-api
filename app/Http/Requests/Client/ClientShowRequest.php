<?php

namespace App\Http\Requests\Client;

use App\Models\Client;
use App\Rules\IsActiveUserRule;
use Illuminate\Foundation\Http\FormRequest;
use Override;

class ClientShowRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'uuid', new IsActiveUserRule(Client::class)],
        ];
    }

    public function attributes(): array
    {
        return [
            'id' => 'ID do Cliente',
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
<?php

namespace App\Http\Requests\Admin;

use App\Models\Admin;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\IsActiveUserRule;
use Override;

class AdminShowRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'uuid', new IsActiveUserRule(Admin::class)],
        ];
    }

    public function attributes(): array
    {
        return [
            'id' => 'ID do Administrador',
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
<?php

namespace App\Http\Requests\Role;

use App\Traits\IndexRequestTrait;
use Illuminate\Foundation\Http\FormRequest;
use Override;

class RoleIndexRequest extends FormRequest
{
    use IndexRequestTrait;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return array_merge($this->paginationRules(), [
            'with_system_admin' => ['sometimes', 'nullable', 'boolean'],
        ]);
    }

    public function attributes(): array
    {
        return [
            'page' => 'Pagina',
            'per_page' => 'Por pagina',
            'search' => 'Filtro',
            'with_system_admin' => 'Incluir administrador do sistema',
        ];
    }

    #[Override]
    protected function prepareForValidation(): void
    {
        if ($this->has('with_system_admin')) {
            $this->merge([
                'with_system_admin' => filter_var(
                    $this->input('with_system_admin'),
                    FILTER_VALIDATE_BOOLEAN,
                    FILTER_NULL_ON_FAILURE
                ),
            ]);
        }
    }
}

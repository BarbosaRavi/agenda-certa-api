<?php

namespace App\Http\Requests\Tenant;

use App\Traits\IndexRequestTrait;
use Illuminate\Foundation\Http\FormRequest;
use Override;

class TenantIndexRequest extends FormRequest
{
    use IndexRequestTrait;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return array_merge($this->paginationRules(), [
            'with_trashed' => ['sometimes', 'nullable', 'boolean']
        ]);
    }

    public function attributes(): array 
    {
        return [
            'with_trashed' => 'Flag para exibir excluidos e ativos',
        ];
    }

    #[Override]
    protected function prepareForValidation(): void
    {
        if ($this->has('with_trashed')) {
            $this->merge(['with_trashed' => filter_var($this->input('with_trashed'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),]);
        }
    }
}
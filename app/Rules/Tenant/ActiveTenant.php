<?php

namespace App\Rules\Tenant;

use App\Models\Tenant;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ActiveTenant implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $exists = Tenant::query()
            ->whereKey($value)
            ->whereHas('user')
            ->exists();

        if (! $exists) {
            $fail('O tenant informado não existe ou está excluído.');
        }
    }
}
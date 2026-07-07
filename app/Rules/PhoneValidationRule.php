<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PhoneValidationRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $phone = preg_replace('/\D/', '', (string) $value);

        if (! preg_match('/^\d{10,11}$/', $phone)) {
            $fail('O telefone deve conter 10 ou 11 dígitos.');
        }
    }
}
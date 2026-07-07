<?php

namespace App\Rules;

use App\Models\Admin;
use App\Models\Client;
use App\Models\Tenant;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Eloquent\Model;

class IsActiveUserRule implements ValidationRule
{
    /**
     * @param class-string<Model> $model
     */
    public function __construct(private readonly string $model) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $exists = $this->model::query()
            ->whereKey($value)
            ->whereHas('user')
            ->exists();

        if (! $exists) {
            $fail("O {$this->label()} informado não existe ou está excluído.");
        }
    }

    private function label(): string
    {
        return match ($this->model) {
            Client::class => 'cliente',
            Tenant::class => 'tenant',
            Admin::class => 'admin',
        };
    }
}
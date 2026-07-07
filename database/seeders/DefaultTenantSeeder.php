<?php

namespace Database\Seeders;

use App\Enums\UserTypeEnum;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DefaultTenantSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::create([
            'name' => 'tenant',
            'email' => 'tenant@example.com',
            'user_type' => UserTypeEnum::TENANT,
            'password' => Hash::make(env('DEFAULT_PASSWORD')),
            'email_verified_at' => now(),
        ]);

        Tenant::create(['user_id' => $user->id]);
        $user->assignRole(UserTypeEnum::TENANT->value)->save();
    }
}

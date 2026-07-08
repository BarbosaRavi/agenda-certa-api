<?php

namespace App\Services\Auth;

use App\Enums\UserTypeEnum;
use App\Exceptions\ApiException;
use App\Http\Resources\User\UserResource;
use App\Models\Client;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService 
{
    public function register(array $data): UserResource
    {
        return DB::transaction(function () use ($data): UserResource {
            $userType = UserTypeEnum::from($data['user_type']);

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'user_type' => $userType,
                'password' => Hash::make($data['password']),
            ]);

            if (isset($data['profile_picture'])) {
                $path = $data['profile_picture']->store('agenda-certa/avatars', 'public');
                $user->update(['profile_picture' => $path]);
            }

            match ($userType) {
                UserTypeEnum::CLIENT => Client::create(['user_id' => $user->id]),
                UserTypeEnum::TENANT => Tenant::create(['user_id' => $user->id]),
                default => throw new ApiException('Tipo de usuario invalido', 422),
            };

            $user->assignRole($userType->value)->save();

            return new UserResource($user->load('roles.permissions'));
        });
    }

    public function login(array $data)
    {
        $user = User::where('email', $data['email'])->first();

        if ($user && Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {

            if ($user && $user->email_verified_at == null) {
                throw new ApiException('É necessário confirmar o email antes', 403);
            }

            $refreshTtlInSeconds = Config::get('jwt.refresh_ttl') * 60;
            $token = JWTAuth::fromUser($user);

            $user->update(['last_login' => now()]);

            return [
                'user' => new UserResource($user->load(['roles.permissions'])), 
                'token' => $token, 
                'refresh_expires_in' => $refreshTtlInSeconds
            ];
        }

        throw new ApiException('Email e/ou senha inválido', 401);
    }

    public function me(): UserResource
    {
        $user = Auth::user();
        return new UserResource($user->load('roles.permissions'));
    }

    public function refreshToken(): array
    {
        $refreshTtlInSeconds = Config::get('jwt.refresh_ttl') * 60;
        $token = auth('api')->refresh();

        return [
            'token' => $token,
            'refresh_expires_in' => $refreshTtlInSeconds,
        ];
    }

    public function logout()
    {
        Auth::logout();
    }
}

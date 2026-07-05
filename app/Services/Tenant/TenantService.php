<?php

namespace App\Services\Tenant;

use App\Enums\UserTypeEnum;
use App\Exceptions\ApiException;
use App\Http\Resources\Admin\AdminResource;
use App\Models\Admin;
use App\Http\Resources\Tenant\TenantCollection;
use App\Http\Resources\Tenant\TenantResource;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TenantService
{
    public function index(array $data): TenantCollection
    {
        $page = $data['page'] ?? 1;
        $perPage = $data['per_page'] ?? 10;
        $search = $data['search'] ?? null;
        $trashed = $data['trashed'] ?? null; 

        $query = Tenant::query()
            ->when($trashed,
                fn ($query) => $query
                    ->whereHas('user', fn ($query) => $query->withTrashed())
                    ->with(['user' => fn ($query) => $query->withTrashed()]),
                fn ($query) => $query
                    ->whereHas('user')
                    ->with('user'))
            ->when($search, function ($query) use ($search, $trashed): void {
                $query->whereHas('user', function ($query) use ($search, $trashed): void {
                    if ($trashed) {
                        $query->withTrashed();
                    }

                $query->where(function ($query) use ($search): void {
                    $query->where('name', 'ILIKE', "%{$search}%")
                        ->orWhere('email', 'ILIKE', "%{$search}%");
                    });
                });
            })
            ->orderBy('created_at')
            ->paginate($perPage, ['*'], 'page', $page);

        return new TenantCollection($query);
    }

    public function show(array $data): TenantResource
    {
        $tenant = Tenant::findOrFail($data['id']);
        return new TenantResource($tenant->load('user'));
    }

    public function store(array $data): AdminResource
    {
        return DB::transaction(function () use ($data): TenantResource {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'user_type' => UserTypeEnum::TENANT,
                'password' => Hash::make($data['password']),
            ]);

            $tenant = Tenant::create(['user_id' => $user->id]);
            $user->assignRole(UserTypeEnum::TENANT->value)->save();

            return new TenantResource($tenant->load('user'));
        });
    }
    
    public function update(array $data): TenantResource
    {
        $tenant = Tenant::findOrFail($data['id']);

        return DB::transaction(function () use ($data, $tenant): TenantResource {
            $updateData = [ 
                'name' => $data['name'],
                'email' => $data['email'],
            ];    
        
            $tenant->user->fill($updateData);

            if ($tenant->user->isDirty('email')) {
                $exists = User::query()
                    ->withTrashed()
                    ->where('email', $data['email'])
                    ->exists();

                if ($exists) {
                    throw new ApiException('Email já está em uso', 422);
                }
            }

            $tenant->user->save();

            return new TenantResource($tenant->load('user'));
        });
    }

    public function delete(array $data): void 
    {
        DB::transaction(function () use ($data): void {
            Tenant::findOrFail($data['id'])->user->delete();
        });
    }

    public function restore(array $data): TenantResource
    {
        return DB::transaction(function () use ($data): TenantResource {
            $tenant = Tenant::findOrFail($data['id']);
            $tenant->user()->withTrashed()->firstOrFail()->restore();
            return new TenantResource($tenant->load('user'));
        });
    }


    public function destroy(array $data): void 
    {
        DB::transaction(function () use ($data): void {
            $tenant = Tenant::findOrFail($data['id']);
            $tenant->user()->withTrashed()->firstOrFail()->forceDelete();
        });
    }
}
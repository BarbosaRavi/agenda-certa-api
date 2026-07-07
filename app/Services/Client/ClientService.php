<?php

namespace App\Services\Client;

use App\Enums\UserTypeEnum;
use App\Exceptions\ApiException;
use App\Http\Resources\Client\ClientCollection;
use App\Http\Resources\Client\ClientResource;
use App\Models\Client;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ClientService
{
    public function index(array $data): ClientCollection
    {
        $page = $data['page'] ?? 1;
        $perPage = $data['per_page'] ?? 10;
        $search = $data['search'] ?? null;
        $trashed = $data['trashed'] ?? false; 

        $query = Client::query()
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
                        ->orWhere('email', 'ILIKE', "%{$search}%")
                        ->orWhere('phone', 'ILIKE', "%{$search}%");
                    });
                });
            })
            ->orderBy('created_at')
            ->paginate($perPage, ['*'], 'page', $page);

        return new ClientCollection($query);
    }

    public function show(array $data): ClientResource
    {
        $client = Client::findOrFail($data['id']);
        return new ClientResource($client->load('user'));
    }

    public function store(array $data): ClientResource
    {
        return DB::transaction(function () use ($data): ClientResource {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'user_type' => UserTypeEnum::CLIENT,
                'password' => Hash::make($data['password']),
            ]);

            $client = Client::create(['user_id' => $user->id]);
            $user->assignRole(UserTypeEnum::CLIENT->value)->save();

            return new ClientResource($client->load('user'));
        });
    }
    
    public function update(array $data): ClientResource
    {
        $client = Client::findOrFail($data['id']);

        return DB::transaction(function () use ($data, $client): ClientResource {
            $updateData = [ 
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
            ];    
        
            $client->user->update($updateData);

            return new ClientResource($client->load('user'));
        });
    }

    public function delete(array $data): void 
    {
        DB::transaction(function () use ($data): void {
            Client::findOrFail($data['id'])->user->delete();
        });
    }

    public function restore(array $data): ClientResource
    {
        return DB::transaction(function () use ($data): ClientResource {
            $client = Client::findOrFail($data['id']);
            $client->user()->withTrashed()->firstOrFail()->restore();
            return new ClientResource($client->load('user'));
        });
    }


    public function destroy(array $data): void 
    {
        DB::transaction(function () use ($data): void {
            $client = Client::findOrFail($data['id']);
            $client->user()->withTrashed()->firstOrFail()->forceDelete();
        });
    }
}
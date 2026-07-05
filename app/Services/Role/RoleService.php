<?php

namespace App\Services\Role;

use App\Enums\UserTypeEnum;
use App\Exceptions\ApiException;
use App\Http\Resources\Role\RoleCollection;
use App\Http\Resources\Role\RoleResource;
use App\Models\Permission;
use App\Models\Role;

class RoleService
{
    public function index(array $data): RoleCollection
    {
        $page = $data['page'] ?? 1;
        $perPage = $data['per_page'] ?? 10;
        $search = $data['search'] ?? null;
        $withSystemAdmin = $data['with_system_admin'] ?? false;

        $query = Role::query()
            ->with('permissions')
            ->when(! $withSystemAdmin, function ($query): void {
                $query->whereNotIn('name', [
                    UserTypeEnum::SYS_ADMIN->value,
                    'system_admin',
                ]);
            })
            ->when($search, function ($query) use ($search): void {
                $query->where('name', 'ILIKE', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate($perPage, ['*'], 'page', $page);

        return new RoleCollection($query);
    }

    public function assignPermission(array $data): RoleResource
    {
        $role = Role::findOrFail($data['id']);
        $permission = Permission::findOrFail($data['permission_id']);

        if ($role->guard_name !== $permission->guard_name) {
            throw new ApiException('A permissao informada nao pertence ao mesmo guard do cargo.', 422);
        }

        $role->givePermissionTo($permission);

        return new RoleResource($role->load('permissions'));
    }
}

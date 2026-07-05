<?php

namespace App\Services\Permission;

use App\Models\Permission;
use Illuminate\Support\Str;

class PermissionService
{
    public function indexWithModules(): array
    {
        $permissionLabels = config('permission_sync.permissions', []);
        $moduleLabels = config('permission_sync.module_labels', []);

        return Permission::query()
            ->orderBy('name')
            ->get()
            ->groupBy(fn (Permission $permission): string => Str::before($permission->name, '.'))
            ->map(function ($permissions, string $module) use ($permissionLabels, $moduleLabels): array {
                return [
                    'name' => $module,
                    'label' => $moduleLabels[$module] ?? $module,
                    'permissions' => $permissions
                        ->map(fn (Permission $permission): array => [
                            'id' => $permission->id,
                            'name' => $permission->name,
                            'label' => $permissionLabels[$permission->name] ?? $permission->name,
                            'guard_name' => $permission->guard_name,
                        ])
                        ->values(),
                ];
            })
            ->values()
            ->all();
    }
}

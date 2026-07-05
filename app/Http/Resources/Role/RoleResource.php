<?php

namespace App\Http\Resources\Role;

use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'guard_name' => $this->guard_name,
            'permissions' => $this->whenLoaded('permissions', function () {
                return $this->permissions
                    ->map(fn (Permission $permission): array => [
                        'id' => $permission->id,
                        'name' => $permission->name,
                        'label' => config("permission_sync.permissions.{$permission->name}"),
                        'guard_name' => $permission->guard_name,
                    ])
                    ->values();
            }),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}

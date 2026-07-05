<?php

namespace App\Http\Controllers\Role;

use App\Builder\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Role\RoleAssignPermissionRequest;
use App\Http\Requests\Role\RoleIndexRequest;
use App\Services\Role\RoleService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;

class RoleController extends Controller
{
    use AuthorizesRequests;

    public function __construct(protected RoleService $service){}

    public function index(RoleIndexRequest $request): JsonResponse
    {
        $roles = $this->service->index($request->validated());
        return ApiResponse::success($roles, "Cargos listados com sucesso!", 200);
    }

    public function assignPermission(RoleAssignPermissionRequest $request): JsonResponse
    {
        $role = $this->service->assignPermission($request->validated());
        return ApiResponse::success($role, "Permissao atribuida ao cargo com sucesso!", 200);
    }
}

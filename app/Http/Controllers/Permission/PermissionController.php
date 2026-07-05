<?php

namespace App\Http\Controllers\Permission;

use App\Builder\ApiResponse;
use App\Http\Controllers\Controller;
use App\Services\Permission\PermissionService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;

class PermissionController extends Controller
{
    use AuthorizesRequests;

    public function __construct(protected PermissionService $service){}

    public function indexWithModules(): JsonResponse
    {
        $permissions = $this->service->indexWithModules();
        return ApiResponse::success($permissions, "Permissoes listadas com sucesso!", 200);
    }
}

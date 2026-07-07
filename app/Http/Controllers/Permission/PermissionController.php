<?php

namespace App\Http\Controllers\Permission;

use App\Builder\ApiResponse;
use App\Http\Controllers\Controller;
use App\Services\Permission\PermissionService;
use Dedoc\Scramble\Attributes\Endpoint;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;

class PermissionController extends Controller
{
    use AuthorizesRequests;

    public function __construct(protected PermissionService $service){}

    #[Endpoint(operationId: 'permission.index', description: 'Lista permissoes agrupadas por modulo. Permission: permissions.view.')]
    public function indexWithModules(): JsonResponse
    {
        $permissions = $this->service->indexWithModules();
        return ApiResponse::success($permissions, "Permissoes listadas com sucesso!", 200);
    }
}

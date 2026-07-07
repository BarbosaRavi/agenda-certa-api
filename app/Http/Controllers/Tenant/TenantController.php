<?php

namespace App\Http\Controllers\Tenant;

use App\Builder\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\TenantStoreRequest;
use App\Http\Requests\Tenant\TenantDeleteRequest;
use App\Http\Requests\Tenant\TenantDestroyRequest;
use App\Http\Requests\Tenant\TenantIndexRequest;
use App\Http\Requests\Tenant\TenantRestoreRequest;
use App\Http\Requests\Tenant\TenantShowRequest;
use App\Http\Requests\Tenant\TenantUpdateRequest;
use App\Services\Tenant\TenantService;
use Dedoc\Scramble\Attributes\Endpoint;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;

class TenantController extends Controller
{
    use AuthorizesRequests;

    public function __construct(protected TenantService $service){}

    #[Endpoint(operationId: 'tenant.index', description: 'Lista inquilinos. Permission: tenant.view.')]
    public function index(TenantIndexRequest $request): JsonResponse
    {
        $tenant = $this->service->index($request->validated());
        return ApiResponse::success($tenant, "Inquilinos listados com sucesso!", 200);
    }

    #[Endpoint(operationId: 'tenant.show', description: 'Exibe um inquilino. Permission: tenant.view.')]
    public function show(TenantShowRequest $request): JsonResponse
    {
        $tenant = $this->service->show($request->validated());
        return ApiResponse::success($tenant, "Inquilino visualizado com sucesso!", 200);
    }

    #[Endpoint(operationId: 'tenant.store', description: 'Cria um inquilino. Permission: tenant.create.')]
    public function store(TenantStoreRequest $request): JsonResponse
    {
        $tenant = $this->service->store($request->validated());
        return ApiResponse::success($tenant, "Inquilino criado com sucesso!", 200);
    }

    #[Endpoint(operationId: 'tenant.update', description: 'Atualiza um inquilino. Permission: tenant.update.')]
    public function update(TenantUpdateRequest $request): JsonResponse
    {
        $tenant = $this->service->update($request->validated());
        return ApiResponse::success($tenant, "Inquilino atualizado com sucesso!", 200);
    }

    #[Endpoint(operationId: 'tenant.delete', description: 'Remove logicamente um inquilino. Permission: tenant.delete.')]
    public function delete(TenantDeleteRequest $request): JsonResponse
    {
        $this->service->delete($request->validated());
        return ApiResponse::success(null, "Inquilino deletado com sucesso!", 200);
    }

    #[Endpoint(operationId: 'tenant.restore', description: 'Restaura um inquilino removido logicamente. Permission: tenant.restore.')]
    public function restore(TenantRestoreRequest $request): JsonResponse
    {
        $tenant = $this->service->restore($request->validated());
        return ApiResponse::success($tenant, "Inquilino restaurado com sucesso!", 200);
    }

    #[Endpoint(operationId: 'tenant.destroy', description: 'Remove permanentemente um inquilino. Permission: tenant.destroy.')]
    public function destroy(TenantDestroyRequest $request): JsonResponse
    {
        $this->service->destroy($request->validated());
        return ApiResponse::success(null, "Inquilino destruido com sucesso!", 200);
    }
}

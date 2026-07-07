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
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;

class TenantController extends Controller
{
    use AuthorizesRequests;

    public function __construct(protected TenantService $service){}

    public function index(TenantIndexRequest $request): JsonResponse
    {
        $tenant = $this->service->index($request->validated());
        return ApiResponse::success($tenant, "Inquilinos listados com sucesso!", 200);
    }

    public function show(TenantShowRequest $request): JsonResponse
    {
        $tenant = $this->service->show($request->validated());
        return ApiResponse::success($tenant, "Inquilino visualizado com sucesso!", 200);
    }

    public function store(TenantStoreRequest $request): JsonResponse
    {
        $tenant = $this->service->store($request->validated());
        return ApiResponse::success($tenant, "Inquilino criado com sucesso!", 200);
    }

    public function update(TenantUpdateRequest $request): JsonResponse
    {
        $tenant = $this->service->update($request->validated());
        return ApiResponse::success($tenant, "Inquilino atualizado com sucesso!", 200);
    }

    public function delete(TenantDeleteRequest $request): JsonResponse
    {
        $this->service->delete($request->validated());
        return ApiResponse::success(null, "Inquilino deletado com sucesso!", 200);
    }

    public function restore(TenantRestoreRequest $request): JsonResponse
    {
        $tenant = $this->service->restore($request->validated());
        return ApiResponse::success($tenant, "Inquilino restaurado com sucesso!", 200);
    }

    public function destroy(TenantDestroyRequest $request): JsonResponse
    {
        $this->service->destroy($request->validated());
        return ApiResponse::success(null, "Inquilino destruido com sucesso!", 200);
    }
}
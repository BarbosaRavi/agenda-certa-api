<?php

namespace App\Http\Controllers\Admin;

use App\Builder\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminDeleteRequest;
use App\Http\Requests\Admin\AdminDestroyRequest;
use App\Http\Requests\Admin\AdminIndexRequest;
use App\Http\Requests\Admin\AdminRestoreRequest;
use App\Http\Requests\Admin\AdminShowRequest;
use App\Http\Requests\Admin\AdminStoreRequest;
use App\Http\Requests\Admin\AdminUpdateRequest;
use App\Services\Admin\AdminService;
use Dedoc\Scramble\Attributes\Endpoint;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;

class AdminController extends Controller
{
    use AuthorizesRequests;

    public function __construct(protected AdminService $service){}

    #[Endpoint(operationId: 'admin.index', description: 'Lista administradores. Permission: admin.view.')]
    public function index(AdminIndexRequest $request): JsonResponse
    {
        $admin = $this->service->index($request->validated());
        return ApiResponse::success($admin, "Administradores listados com sucesso!", 200);
    }

    #[Endpoint(operationId: 'admin.show', description: 'Exibe um administrador. Permission: admin.view.')]
    public function show(AdminShowRequest $request): JsonResponse
    {
        $admin = $this->service->show($request->validated());
        return ApiResponse::success($admin, "Administrador visualizado com sucesso!", 200);
    }

    #[Endpoint(operationId: 'admin.store', description: 'Cria um administrador. Permission: admin.create.')]
    public function store(AdminStoreRequest $request): JsonResponse
    {
        $admin = $this->service->store($request->validated());
        return ApiResponse::success($admin, "Administrador criado com sucesso!", 200);
    }

    #[Endpoint(operationId: 'admin.update', description: 'Atualiza um administrador. Permission: admin.update.')]
    public function update(AdminUpdateRequest $request): JsonResponse
    {
        $admin = $this->service->update($request->validated());
        return ApiResponse::success($admin, "Administrador atualizado com sucesso!", 200);
    }

    #[Endpoint(operationId: 'admin.delete', description: 'Remove logicamente um administrador. Permission: admin.delete.')]
    public function delete(AdminDeleteRequest $request): JsonResponse
    {
        $this->service->delete($request->validated());
        return ApiResponse::success(null, "Administrador deletado com sucesso!", 200);
    }

    #[Endpoint(operationId: 'admin.restore', description: 'Restaura um administrador removido logicamente. Permission: admin.restore.')]
    public function restore(AdminRestoreRequest $request): JsonResponse
    {
        $admin = $this->service->restore($request->validated());
        return ApiResponse::success($admin, "Administrador restaurado com sucesso!", 200);
    }

    #[Endpoint(operationId: 'admin.destroy', description: 'Remove permanentemente um administrador. Permission: admin.destroy.')]
    public function destroy(AdminDestroyRequest $request): JsonResponse
    {
        $this->service->destroy($request->validated());
        return ApiResponse::success(null, "Administrador destruido com sucesso!", 200);
    }
}

<?php

namespace App\Http\Controllers\Client;

use App\Builder\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Client\ClientStoreRequest;
use App\Http\Requests\Client\ClientDeleteRequest;
use App\Http\Requests\Client\ClientDestroyRequest;
use App\Http\Requests\Client\ClientIndexRequest;
use App\Http\Requests\Client\ClientRestoreRequest;
use App\Http\Requests\Client\ClientShowRequest;
use App\Http\Requests\Client\ClientUpdateRequest;
use App\Services\Client\ClientService;
use Dedoc\Scramble\Attributes\Endpoint;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;

class ClientController extends Controller
{
    use AuthorizesRequests;

    public function __construct(protected ClientService $service){}

    #[Endpoint(operationId: 'client.index', description: 'Lista clientes. Permission: client.view.')]
    public function index(ClientIndexRequest $request): JsonResponse
    {
        $client = $this->service->index($request->validated());
        return ApiResponse::success($client, "Clientes listados com sucesso!", 200);
    }

    #[Endpoint(operationId: 'client.show', description: 'Exibe um cliente. Permission: client.view.')]
    public function show(ClientShowRequest $request): JsonResponse
    {
        $client = $this->service->show($request->validated());
        return ApiResponse::success($client, "Cliente visualizado com sucesso!", 200);
    }

    #[Endpoint(operationId: 'client.store', description: 'Cria um cliente. Permission: client.create.')]
    public function store(ClientStoreRequest $request): JsonResponse
    {
        $client = $this->service->store($request->validated());
        return ApiResponse::success($client, "Cliente criado com sucesso!", 200);
    }

    #[Endpoint(operationId: 'client.update', description: 'Atualiza um cliente. Permission: client.update.')]
    public function update(ClientUpdateRequest $request): JsonResponse
    {
        $client = $this->service->update($request->validated());
        return ApiResponse::success($client, "Cliente atualizado com sucesso!", 200);
    }

    #[Endpoint(operationId: 'client.delete', description: 'Remove logicamente um cliente. Permission: client.delete.')]
    public function delete(ClientDeleteRequest $request): JsonResponse
    {
        $this->service->delete($request->validated());
        return ApiResponse::success(null, "Cliente deletado com sucesso!", 200);
    }

    #[Endpoint(operationId: 'client.restore', description: 'Restaura um cliente removido logicamente. Permission: client.restore.')]
    public function restore(ClientRestoreRequest $request): JsonResponse
    {
        $client = $this->service->restore($request->validated());
        return ApiResponse::success($client, "Cliente restaurado com sucesso!", 200);
    }

    #[Endpoint(operationId: 'client.destroy', description: 'Remove permanentemente um cliente. Permission: client.destroy.')]
    public function destroy(ClientDestroyRequest $request): JsonResponse
    {
        $this->service->destroy($request->validated());
        return ApiResponse::success(null, "Cliente destruido com sucesso!", 200);
    }
}

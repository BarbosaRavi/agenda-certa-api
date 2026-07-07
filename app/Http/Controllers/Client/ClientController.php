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
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;

class ClientController extends Controller
{
    use AuthorizesRequests;

    public function __construct(protected ClientService $service){}

    public function index(ClientIndexRequest $request): JsonResponse
    {
        $client = $this->service->index($request->validated());
        return ApiResponse::success($client, "Clientes listados com sucesso!", 200);
    }

    public function show(ClientShowRequest $request): JsonResponse
    {
        $client = $this->service->show($request->validated());
        return ApiResponse::success($client, "Cliente visualizado com sucesso!", 200);
    }

    public function store(ClientStoreRequest $request): JsonResponse
    {
        $client = $this->service->store($request->validated());
        return ApiResponse::success($client, "Cliente criado com sucesso!", 200);
    }

    public function update(ClientUpdateRequest $request): JsonResponse
    {
        $client = $this->service->update($request->validated());
        return ApiResponse::success($client, "Cliente atualizado com sucesso!", 200);
    }

    public function delete(ClientDeleteRequest $request): JsonResponse
    {
        $this->service->delete($request->validated());
        return ApiResponse::success(null, "Cliente deletado com sucesso!", 200);
    }

    public function restore(ClientRestoreRequest $request): JsonResponse
    {
        $client = $this->service->restore($request->validated());
        return ApiResponse::success($client, "Cliente restaurado com sucesso!", 200);
    }

    public function destroy(ClientDestroyRequest $request): JsonResponse
    {
        $this->service->destroy($request->validated());
        return ApiResponse::success(null, "Cliente destruido com sucesso!", 200);
    }
}
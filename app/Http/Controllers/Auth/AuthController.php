<?php

namespace App\Http\Controllers\Auth;

use App\Builder\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\Auth\AuthService;
use Dedoc\Scramble\Attributes\Endpoint;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    use AuthorizesRequests;

    public function __construct(protected AuthService $service){}

    #[Endpoint(operationId: 'auth.login', description: 'Autentica o usuario e retorna os tokens de acesso.')]
    public function login(LoginRequest $request): JsonResponse
    {
        $admin = $this->service->login($request->validated());
        return ApiResponse::success($admin, "Usuário com sucesso!", 200);
    }

    #[Endpoint(operationId: 'auth.me', description: 'Retorna os dados do usuario autenticado.')]
    public function me(): JsonResponse
    {
        $admin = $this->service->me();
        return ApiResponse::success($admin, "Dados do usuário", 200);
    }

    #[Endpoint(operationId: 'auth.refreshToken', description: 'Atualiza o token de acesso.')]
    public function refreshToken(): JsonResponse
    {
        $admin = $this->service->refreshToken();
        return ApiResponse::success($admin, "Token atualizado com sucesso!", 200);
    }
}

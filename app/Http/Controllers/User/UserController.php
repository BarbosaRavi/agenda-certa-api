<?php

namespace App\Http\Controllers\User;

use App\Builder\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\ChangePasswordRequest;
use App\Http\Requests\User\ForgotPasswordRequest;
use App\Http\Requests\User\ResetPasswordRequest;
use App\Http\Requests\User\ConfirmMailRequest;
use App\Http\Requests\User\UploadProfilePictureRequest;
use App\Http\Requests\User\ResendMailConfirmationRequest;
use App\Services\User\UserService;
use Dedoc\Scramble\Attributes\Endpoint;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    use AuthorizesRequests;

    public function __construct(protected UserService $service){}

    #[Endpoint(operationId: 'user.updatePassword', description: 'Atualiza a senha do usuario autenticado.')]
    public function updatePassword(ChangePasswordRequest $request): JsonResponse
    {
        $admin = $this->service->changePassword($request->validated());
        return ApiResponse::success($admin, "Senha atualizada com sucesso!", 200);
    }

    #[Endpoint(operationId: 'user.forgotPassword', description: 'Envia email de recuperacao de senha.')]
    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $admin = $this->service->forgotPassword($request->validated());
        return ApiResponse::success($admin, "Email de recuperação de senha enviado com sucesso!", 200);
    }

    #[Endpoint(operationId: 'user.resetPassword', description: 'Redefine a senha do usuario.')]
    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $admin = $this->service->resetPassword($request->validated());
        return ApiResponse::success($admin, "Senha redefinida com sucesso!", 200);
    }

    #[Endpoint(operationId: 'user.confirmMail', description: 'Confirma o email do usuario.')]
    public function confirmMail(ConfirmMailRequest $request): JsonResponse
    {
        $this->service->confirmMail($request->validated());

        return ApiResponse::success(null, 'Email confirmado com sucesso!', 200);
    }

    #[Endpoint(operationId: 'user.resendMailConfirmation', description: 'Reenvia email de confirmacao.')]
    public function resendMailConfirmation(ResendMailConfirmationRequest $request): JsonResponse
    {
        $this->service->resendMailConfirmation($request->validated());

        return ApiResponse::success(null, 'Email de confirmação reenviado com sucesso!', 200);
    }

    #[Endpoint(operationId: 'user.uploadProfilePicture', description: 'Atualiza a foto de perfil do usuario. Permission: users.update.')]
    public function uploadProfilePicture(UploadProfilePictureRequest  $request): JsonResponse
    {
        $this->service->uploadProfilePicture($request->validated());

        return ApiResponse::success(null, 'Foto de perfil atualizada com sucesso!', 200);
    }
}

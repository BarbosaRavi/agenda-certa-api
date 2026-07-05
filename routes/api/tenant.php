<?php 

use App\Http\Controllers\Tenant\TenantController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth.api', 'can:tenant.view'])->group(function () {
    Route::get('/', [TenantController::class, 'index']);
    Route::get('/{id}', [TenantController::class, 'show']);
});

Route::middleware(['auth.api', 'can:tenant.create'])->group(function () {
    Route::post('/', [TenantController::class, 'store']);
});

Route::middleware(['auth.api', 'can:tenant.update'])->group(function () {
    Route::put('/{id}', [TenantController::class, 'update']);   
});

Route::middleware(['auth.api', 'can:tenant.restore'])->group(function () {
    Route::patch('/restore/{id}', [TenantController::class, 'restore']);
});

Route::middleware(['auth.api', 'can:tenant.destroy'])->group(function () {
    Route::delete('/destroy/{id}', [TenantController::class, 'destroy']);
});

Route::middleware(['auth.api', 'can:tenant.delete'])->group(function () {
    Route::delete('/{id}', [TenantController::class, 'delete']);
});
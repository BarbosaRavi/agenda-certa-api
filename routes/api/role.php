<?php

use App\Http\Controllers\Role\RoleController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth.api', 'can:roles.view'])->group(function () {
    Route::get('/', [RoleController::class, 'index']);
});

Route::middleware(['auth.api', 'can:roles.assign-permission'])->group(function () {
    Route::post('/{id}/permissions', [RoleController::class, 'assignPermission']);
});

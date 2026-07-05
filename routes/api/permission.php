<?php

use App\Http\Controllers\Permission\PermissionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth.api', 'can:permissions.view'])->group(function () {
    Route::get('/modules', [PermissionController::class, 'indexWithModules']);
});

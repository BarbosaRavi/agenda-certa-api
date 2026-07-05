<?php

use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('/auth')->group(base_path('routes/api/auth.php'));
Route::prefix('/admin')->group(base_path('routes/api/admin.php'));
Route::prefix('/tenant')->group(base_path('routes/api/tenant.php'));
Route::prefix('/role')->group(base_path('routes/api/role.php'));
Route::prefix('/permission')->group(base_path('routes/api/permission.php'));
Route::prefix('/user')->group(base_path('routes/api/user.php'));

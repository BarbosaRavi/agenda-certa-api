<?php 

use App\Http\Controllers\Client\ClientController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth.api', 'can:client.view'])->group(function () {
    Route::get('/', [ClientController::class, 'index']);
    Route::get('/{id}', [ClientController::class, 'show']);
});

Route::middleware(['auth.api', 'can:client.create'])->group(function () {
    Route::post('/', [ClientController::class, 'store']);
});

Route::middleware(['auth.api', 'can:client.update'])->group(function () {
    Route::put('/{id}', [ClientController::class, 'update']);   
});

Route::middleware(['auth.api', 'can:client.restore'])->group(function () {
    Route::patch('/restore/{id}', [ClientController::class, 'restore']);
});

Route::middleware(['auth.api', 'can:client.destroy'])->group(function () {
    Route::delete('/destroy/{id}', [ClientController::class, 'destroy']);
});

Route::middleware(['auth.api', 'can:client.delete'])->group(function () {
    Route::delete('/{id}', [ClientController::class, 'delete']);
});
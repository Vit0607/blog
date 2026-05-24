<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function (): void {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function (): void {
        Route::get('me', [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
});

Route::get('posts', [PostController::class, 'index']);
Route::get('post/{id}', [PostController::class, 'show']);

Route::middleware('auth:sanctum')->group(function (): void {
    Route::apiResource('posts', PostController::class)->except('create', 'edit');
});
<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function (): void {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login'])->middleware('throttle:login');

    Route::middleware('auth:sanctum')->group(function (): void {
        Route::get('me', [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
});

Route::middleware(['auth:sanctum', 'throttle:api'])->group(function (): void {
    Route::get('/posts/trashed', [PostController::class, 'trashed']);
    Route::apiResource('posts', PostController::class)->except('create', 'edit');
    Route::post('/posts/{id}/restore', [PostController::class, 'restore']);
    Route::delete('/posts/{id}/force', [PostController::class, 'forceDelete']);
});

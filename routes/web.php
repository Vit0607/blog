<?php

use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;


Route::get('/', fn () => redirect()->route('blog.index'))->name('index');

Route::prefix('admin')->as('admin.')->middleware(['auth', 'role:admin'])->group(function() { // route('admin.posts.index')
    Route::resource('posts', AdminPostController::class);
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::patch('users/{userId}/role', [UserController::class, 'updateRole'])->name('users.update.role');
});

Route::prefix('blog')->as('blog.')->group(function() {
    Route::get('/', [PostController::class, 'index'])->name('index');

    Route::get('/{post:slug}', [PostController::class, 'show'])->name('show');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');

    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'resetPassword'])->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

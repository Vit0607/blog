<?php

use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('blog.index'))->name('index');

Route::prefix('admin')->as('admin.')->middleware('auth')->group(function() { // route('admin.posts.index')
    Route::resource('posts', AdminPostController::class);
});

Route::prefix('blog')->as('blog.')->group(function() {
    Route::get('/', [PostController::class, 'index'])->name('index');

    Route::get('/{post:slug}', [PostController::class, 'show'])->name('show');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
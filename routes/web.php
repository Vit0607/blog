<?php

use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('blog.index'))->name('index');

Route::prefix('admin')->as('admin.')->group(function() { // route('admin.posts.index')
    Route::resource('posts', AdminPostController::class);
});

Route::prefix('blog')->as('blog.')->group(function() {
    Route::get('/', [PostController::class, 'index'])->name('index');

    Route::get('/{post:slug}', [PostController::class, 'show'])->name('show');
});
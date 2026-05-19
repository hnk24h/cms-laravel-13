<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\NowController;
use App\Http\Controllers\Api\PostController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Posts
    Route::get('/posts', [PostController::class, 'index'])->name('api.posts.index');
    Route::get('/posts/slugs', [PostController::class, 'slugs'])->name('api.posts.slugs');
    Route::get('/posts/{slug}/related', [PostController::class, 'related'])->name('api.posts.related');
    Route::get('/posts/{slug}', [PostController::class, 'show'])->name('api.posts.show');

    // Categories
    Route::get('/categories', [CategoryController::class, 'index'])->name('api.categories.index');

    // Now page
    Route::get('/now', [NowController::class, 'show'])->name('api.now.show');
});

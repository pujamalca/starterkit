<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\CommentController;
use App\Http\Controllers\Api\V1\PageController;
use App\Http\Controllers\Api\V1\PostController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')
    ->name('api.v1.')
    ->middleware(['api'])
    ->group(function (): void {
        Route::prefix('auth')->group(function (): void {
            Route::post('register', [AuthController::class, 'register'])->name('auth.register');
            Route::post('login', [AuthController::class, 'login'])->name('auth.login');

            Route::middleware(['auth:sanctum', 'active'])->group(function (): void {
                Route::post('logout', [AuthController::class, 'logout'])->name('auth.logout');
                Route::get('profile', [AuthController::class, 'profile'])->name('auth.profile');
            });
        });

        Route::get('posts', [PostController::class, 'index'])->name('posts.index');
        Route::get('posts/{post:slug}', [PostController::class, 'show'])->name('posts.show');

        Route::get('pages', [PageController::class, 'index'])->name('pages.index');
        Route::get('pages/{slug}', [PageController::class, 'show'])->name('pages.show');

        Route::middleware(['auth:sanctum', 'active'])->group(function (): void {
            Route::post('posts', [PostController::class, 'store'])->name('posts.store');
            Route::match(['put', 'patch'], 'posts/{post}', [PostController::class, 'update'])->name('posts.update');
            Route::delete('posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
        });

        Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('categories/{category:slug}', [CategoryController::class, 'show'])->name('categories.show');
        Route::get('categories/{category:slug}/posts', [CategoryController::class, 'posts'])->name('categories.posts');

        Route::get('comments', [CommentController::class, 'index'])->name('comments.index');
        Route::get('posts/{post:slug}/comments', [CommentController::class, 'forPost'])->name('posts.comments.index');
        Route::post('posts/{post:slug}/comments', [CommentController::class, 'store'])->name('posts.comments.store');

        Route::middleware(['auth:sanctum', 'active'])->group(function (): void {
            Route::post('comments/{comment}/approve', [CommentController::class, 'approve'])->name('comments.approve');
            Route::delete('comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
        });
    });

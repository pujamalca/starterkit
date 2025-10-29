<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostPreviewController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Blog routes
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// Pages routes
Route::get('/pages/{slug}', [PageController::class, 'show'])->name('pages.show');

// Preview route (protected with auth middleware)
Route::middleware('auth')->group(function () {
    Route::get('/preview/posts/{post}', [PostPreviewController::class, 'show'])->name('posts.preview');
    Route::get('/preview/pages/{page}', [PageController::class, 'preview'])->name('pages.preview');
});

<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\CommentController;
use App\Http\Controllers\Api\V1\PageController;
use App\Http\Controllers\Api\V1\PostController;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Simple search endpoint for landing page
Route::get('/search', function (Request $request) {
    $query = $request->get('q', '');

    if (strlen($query) < 2) {
        return response()->json(['data' => []]);
    }

    $posts = Post::query()
        ->where('status', 'published')
        ->where(function($q) use ($query) {
            $q->where('title', 'LIKE', "%{$query}%")
              ->orWhere('excerpt', 'LIKE', "%{$query}%")
              ->orWhere('content', 'LIKE', "%{$query}%");
        })
        ->with('category')
        ->orderBy('published_at', 'desc')
        ->limit(10)
        ->get()
        ->map(function($post) {
            return [
                'slug' => $post->slug,
                'title' => $post->title,
                'excerpt' => $post->excerpt,
                'category' => $post->category->name ?? 'Uncategorized',
                'date' => $post->published_at?->format('d M Y') ?? '',
            ];
        });

    return response()->json(['data' => $posts]);
})->middleware('throttle:60,1');

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

        Route::get('posts', [PostController::class, 'index'])
            ->middleware('throttle:public-content')
            ->name('posts.index');
        Route::get('posts/{post:slug}', [PostController::class, 'show'])
            ->middleware('throttle:public-content')
            ->name('posts.show');

        Route::get('pages', [PageController::class, 'index'])
            ->middleware('throttle:public-content')
            ->name('pages.index');
        Route::get('pages/{slug}', [PageController::class, 'show'])
            ->middleware('throttle:public-content')
            ->name('pages.show');

        Route::middleware(['auth:sanctum', 'active', 'abilities:posts:write', 'throttle:content-write'])->group(function (): void {
            Route::post('posts', [PostController::class, 'store'])->name('posts.store');
            Route::match(['put', 'patch'], 'posts/{post}', [PostController::class, 'update'])->name('posts.update');
            Route::delete('posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
        });

        Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('categories/{category:slug}', [CategoryController::class, 'show'])->name('categories.show');
        Route::get('categories/{category:slug}/posts', [CategoryController::class, 'posts'])->name('categories.posts');

        Route::get('comments', [CommentController::class, 'index'])->name('comments.index');
        Route::get('posts/{post:slug}/comments', [CommentController::class, 'forPost'])->name('posts.comments.index');
        Route::post('posts/{post:slug}/comments', [CommentController::class, 'store'])
            ->middleware('throttle:comments')
            ->name('posts.comments.store');

        Route::middleware(['auth:sanctum', 'active', 'abilities:comments:moderate'])->group(function (): void {
            Route::post('comments/{comment}/approve', [CommentController::class, 'approve'])->name('comments.approve');
            Route::delete('comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
        });
    });

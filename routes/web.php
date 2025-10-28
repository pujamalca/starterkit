<?php

use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/pages/{slug}', [PageController::class, 'show'])->name('pages.show');

// Preview route (protected with auth middleware)
Route::middleware('auth')->group(function () {
    Route::get('/preview/pages/{page}', [PageController::class, 'preview'])->name('pages.preview');
});

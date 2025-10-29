<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostPreviewController extends Controller
{
    public function show(Request $request, Post $post): View
    {
        // Preview dapat diakses untuk semua status (draft, scheduled, published)
        return view('posts.show', [
            'post' => $post->load(['category', 'author', 'tags']),
            'preview' => true,
        ]);
    }
}

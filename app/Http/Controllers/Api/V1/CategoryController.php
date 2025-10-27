<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\PostResource;
use App\Models\Category;
use App\Services\CategoryService;
use App\Services\PostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(
        protected readonly CategoryService $categoryService,
        protected readonly PostService $postService,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only([
            'per_page',
            'is_active',
            'is_featured',
            'only_root',
            'search',
        ]);

        $categories = $this->categoryService->list($filters);

        return CategoryResource::collection($categories)->response();
    }

    public function show(Category $category): CategoryResource
    {
        return CategoryResource::make(
            $category->loadMissing(['parent', 'children'])
        );
    }

    public function posts(Request $request, Category $category): JsonResponse
    {
        $filters = $request->only([
            'per_page',
            'search',
            'tag',
            'author',
            'sort',
            'direction',
        ]);

        $posts = $this->postService->listByCategory($category->id, $filters);

        return PostResource::collection($posts)->response();
    }
}

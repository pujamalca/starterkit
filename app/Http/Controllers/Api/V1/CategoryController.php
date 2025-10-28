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
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Categories",
 *     description="Kategori konten dan relasinya."
 * )
 */
class CategoryController extends Controller
{
    public function __construct(
        protected readonly CategoryService $categoryService,
        protected readonly PostService $postService,
    ) {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/categories",
     *     summary="Daftar kategori",
     *     tags={"Categories"},
     *     @OA\Parameter(name="is_active", in="query", @OA\Schema(type="boolean")),
     *     @OA\Parameter(name="is_featured", in="query", @OA\Schema(type="boolean")),
     *     @OA\Parameter(name="only_root", in="query", @OA\Schema(type="boolean")),
     *     @OA\Parameter(name="search", in="query", @OA\Schema(type="string")),
     *     @OA\Response(
     *         response=200,
     *         description="Daftar kategori berhasil diambil",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/CategoryResource")
     *             ),
     *             @OA\Property(property="links", type="object"),
     *             @OA\Property(property="meta", type="object")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/v1/categories/{category}",
     *     summary="Detail kategori",
     *     tags={"Categories"},
     *     @OA\Parameter(
     *         name="category",
     *         in="path",
     *         required=true,
     *         description="Slug kategori",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detail kategori berhasil diambil",
     *         @OA\JsonContent(ref="#/components/schemas/CategoryResource")
     *     ),
     *     @OA\Response(response=404, description="Kategori tidak ditemukan")
     * )
     */
    public function show(Category $category): CategoryResource
    {
        return CategoryResource::make(
            $category->loadMissing(['parent', 'children'])
        );
    }

    /**
     * @OA\Get(
     *     path="/api/v1/categories/{category}/posts",
     *     summary="Daftar post pada kategori tertentu",
     *     tags={"Categories"},
     *     @OA\Parameter(
     *         name="category",
     *         in="path",
     *         required=true,
     *         description="Slug kategori",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Daftar post berhasil diambil",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/PostResource")
     *             ),
     *             @OA\Property(property="links", type="object"),
     *             @OA\Property(property="meta", type="object")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Kategori tidak ditemukan")
     * )
     */
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

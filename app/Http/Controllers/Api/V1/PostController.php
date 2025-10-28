<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Posts",
 *     description="Manajemen dan publikasi konten post."
 * )
 */
class PostController extends Controller
{
    public function __construct(
        protected readonly PostService $postService,
    ) {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/posts",
     *     summary="Daftar postingan publik",
     *     tags={"Posts"},
     *     @OA\Parameter(name="search", in="query", description="Kata kunci pencarian", @OA\Schema(type="string")),
     *     @OA\Parameter(name="category_slug", in="query", description="Filter berdasarkan slug kategori", @OA\Schema(type="string")),
     *     @OA\Parameter(name="tag", in="query", description="Filter berdasarkan slug atau ID tag", @OA\Schema(type="string")),
     *     @OA\Parameter(name="author", in="query", description="Filter berdasarkan username/email penulis", @OA\Schema(type="string")),
     *     @OA\Parameter(name="per_page", in="query", description="Jumlah data per halaman", @OA\Schema(type="integer", default=15)),
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
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only([
            'search',
            'category_id',
            'category_slug',
            'author',
            'author_id',
            'tag',
            'per_page',
            'sort',
            'direction',
            'is_featured',
        ]);

        $posts = $this->postService->listPublished($filters);

        return PostResource::collection($posts)->response();
    }

    /**
     * @OA\Get(
     *     path="/api/v1/posts/{post}",
     *     summary="Detail post",
     *     tags={"Posts"},
     *     @OA\Parameter(
     *         name="post",
     *         in="path",
     *         description="Slug post",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detail post berhasil diambil",
     *         @OA\JsonContent(ref="#/components/schemas/PostResource")
     *     ),
     *     @OA\Response(response=404, description="Post tidak ditemukan")
     * )
     */
    public function show(Request $request, Post $post): PostResource
    {
        $user = $request->user();
        $canManage = $user?->can('manage-posts') ?? false;
        $isAuthor = $user && $post->author_id === $user->id;

        if ($post->status !== 'published' && ! $canManage && ! $isAuthor) {
            abort(Response::HTTP_NOT_FOUND);
        }

        if ($post->status === 'published') {
            $post->incrementViews();
            $post->refresh();
        }

        return PostResource::make($post->loadMissing(['category', 'author', 'tags']));
    }

    /**
     * @OA\Post(
     *     path="/api/v1/posts",
     *     summary="Buat post baru",
     *     tags={"Posts"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/PostResource")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Post berhasil dibuat",
     *         @OA\JsonContent(ref="#/components/schemas/PostResource")
     *     ),
     *     @OA\Response(response=401, description="Tidak terautentikasi"),
     *     @OA\Response(response=403, description="Tidak memiliki izin"),
     *     @OA\Response(response=422, description="Validasi gagal")
     * )
     */
    public function store(StorePostRequest $request): JsonResponse
    {
        if (! $request->user()?->tokenCan('posts:write')) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $post = $this->postService->create($request->user(), $request->validated());

        return PostResource::make($post)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/posts/{post}",
     *     summary="Perbarui post",
     *     tags={"Posts"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="post",
     *         in="path",
     *         description="Slug post",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/PostResource")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Post berhasil diperbarui",
     *         @OA\JsonContent(ref="#/components/schemas/PostResource")
     *     ),
     *     @OA\Response(response=401, description="Tidak terautentikasi"),
     *     @OA\Response(response=403, description="Tidak memiliki izin"),
     *     @OA\Response(response=404, description="Post tidak ditemukan")
     * )
     */
    public function update(UpdatePostRequest $request, Post $post): JsonResponse
    {
        if (! $request->user()?->tokenCan('posts:write')) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $post = $this->postService->update($post, $request->validated());

        return PostResource::make($post)
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/posts/{post}",
     *     summary="Hapus post",
     *     tags={"Posts"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="post",
     *         in="path",
     *         required=true,
     *         description="Slug post",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=204, description="Post dihapus"),
     *     @OA\Response(response=401, description="Tidak terautentikasi"),
     *     @OA\Response(response=403, description="Tidak memiliki izin"),
     *     @OA\Response(response=404, description="Post tidak ditemukan")
     * )
     */
    public function destroy(Request $request, Post $post): JsonResponse
    {
        $user = $request->user();

        if (! $user?->tokenCan('posts:write')) {
            abort(Response::HTTP_FORBIDDEN);
        }

        if (! $user || (! $user->can('manage-posts') && $user->id !== $post->author_id)) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $this->postService->delete($post);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}

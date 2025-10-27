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

class PostController extends Controller
{
    public function __construct(
        protected readonly PostService $postService,
    ) {
    }

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

    public function store(StorePostRequest $request): JsonResponse
    {
        $post = $this->postService->create($request->user(), $request->validated());

        return PostResource::make($post)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function update(UpdatePostRequest $request, Post $post): JsonResponse
    {
        $post = $this->postService->update($post, $request->validated());

        return PostResource::make($post)
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(Request $request, Post $post): JsonResponse
    {
        $user = $request->user();

        if (! $user || (! $user->can('manage-posts') && $user->id !== $post->author_id)) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $this->postService->delete($post);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}

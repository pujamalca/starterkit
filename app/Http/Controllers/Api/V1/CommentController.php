<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\StoreCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use App\Services\CommentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CommentController extends Controller
{
    public function __construct(
        protected readonly CommentService $commentService,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only([
            'status',
            'per_page',
            'commentable_type',
            'commentable_id',
            'user_id',
            'search',
        ]);

        if (! isset($filters['status'])) {
            $filters['status'] = 'approved';
        }

        $comments = $this->commentService->list($filters);

        return CommentResource::collection($comments)->response();
    }

    public function forPost(Request $request, Post $post): JsonResponse
    {
        $user = $request->user();
        $canManage = $user?->can('manage-posts') ?? false;
        $isAuthor = $user && $post->author_id === $user->id;

        if ($post->status !== 'published' && ! $canManage && ! $isAuthor) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $filters = $request->only(['per_page', 'search']);

        $comments = $this->commentService->listForPost($post, $filters);

        return CommentResource::collection($comments)->response();
    }

    public function store(StoreCommentRequest $request, Post $post): JsonResponse
    {
        $user = $request->user();
        $canManage = $user?->can('manage-posts') ?? false;
        $isAuthor = $user && $post->author_id === $user->id;

        if ($post->status !== 'published' && ! $canManage && ! $isAuthor) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $comment = $this->commentService->create($post, $user, $request->validated());

        $resource = CommentResource::make($comment->loadMissing('user'));
        $meta = [
            'status' => $comment->isApproved() ? 'approved' : 'pending',
            'requires_moderation' => ! $comment->isApproved(),
        ];

        $response = $resource->additional(['meta' => $meta])->response();

        $status = $comment->isApproved() ? Response::HTTP_CREATED : Response::HTTP_ACCEPTED;

        return $response->setStatusCode($status);
    }

    public function approve(Request $request, Comment $comment): JsonResponse
    {
        $this->ensureCanModerate($request);

        $comment = $this->commentService->approve($comment, $request->user());

        return CommentResource::make($comment)->response();
    }

    public function destroy(Request $request, Comment $comment): JsonResponse
    {
        $this->ensureCanModerate($request);

        $this->commentService->delete($comment);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }

    protected function ensureCanModerate(Request $request): void
    {
        if (! ($request->user()?->can('manage-comments') ?? false)) {
            abort(Response::HTTP_FORBIDDEN);
        }
    }
}

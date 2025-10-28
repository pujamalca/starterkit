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
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Comments",
 *     description="Manajemen komentar pada konten."
 * )
 */
class CommentController extends Controller
{
    public function __construct(
        protected readonly CommentService $commentService,
    ) {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/comments",
     *     summary="Daftar komentar",
     *     tags={"Comments"},
     *     @OA\Parameter(name="status", in="query", @OA\Schema(type="string", enum={"approved","pending"})),
     *     @OA\Parameter(name="commentable_type", in="query", @OA\Schema(type="string")),
     *     @OA\Parameter(name="commentable_id", in="query", @OA\Schema(type="integer")),
     *     @OA\Response(
     *         response=200,
     *         description="Daftar komentar berhasil diambil",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/CommentResource")
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

    /**
     * @OA\Get(
     *     path="/api/v1/posts/{post}/comments",
     *     summary="Daftar komentar untuk post",
     *     tags={"Comments"},
     *     @OA\Parameter(
     *         name="post",
     *         in="path",
     *         required=true,
     *         description="Slug post",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Daftar komentar berhasil diambil",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/CommentResource")
     *             ),
     *             @OA\Property(property="links", type="object"),
     *             @OA\Property(property="meta", type="object")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Post tidak ditemukan")
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/api/v1/posts/{post}/comments",
     *     summary="Kirim komentar pada post",
     *     tags={"Comments"},
     *     @OA\Parameter(
     *         name="post",
     *         in="path",
     *         required=true,
     *         description="Slug post",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"content"},
     *             @OA\Property(property="content", type="string", example="Komentar yang sangat membantu"),
     *             @OA\Property(property="parent_id", type="integer", nullable=true),
     *             @OA\Property(property="guest_name", type="string", nullable=true),
     *             @OA\Property(property="guest_email", type="string", format="email", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Komentar tersimpan (langsung approve)",
     *         @OA\JsonContent(ref="#/components/schemas/CommentResource")
     *     ),
     *     @OA\Response(
     *         response=202,
     *         description="Komentar tersimpan dan menunggu moderasi",
     *         @OA\JsonContent(ref="#/components/schemas/CommentResource")
     *     ),
     *     @OA\Response(response=404, description="Post tidak ditemukan"),
     *     @OA\Response(response=422, description="Validasi gagal")
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/api/v1/comments/{comment}/approve",
     *     summary="Approve komentar",
     *     tags={"Comments"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="comment",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Komentar berhasil di-approve",
     *         @OA\JsonContent(ref="#/components/schemas/CommentResource")
     *     ),
     *     @OA\Response(response=403, description="Tidak memiliki izin"),
     *     @OA\Response(response=404, description="Komentar tidak ditemukan")
     * )
     */
    public function approve(Request $request, Comment $comment): JsonResponse
    {
        $this->ensureCanModerate($request);

        $comment = $this->commentService->approve($comment, $request->user());

        return CommentResource::make($comment)->response();
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/comments/{comment}",
     *     summary="Hapus komentar",
     *     tags={"Comments"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="comment",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Komentar dihapus"),
     *     @OA\Response(response=403, description="Tidak memiliki izin"),
     *     @OA\Response(response=404, description="Komentar tidak ditemukan")
     * )
     */
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

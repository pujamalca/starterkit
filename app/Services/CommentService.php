<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Settings\GeneralSettings;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class CommentService
{
    public function __construct(
        protected readonly GeneralSettings $settings,
    ) {
    }

    public function list(array $filters = []): LengthAwarePaginator
    {
        $perPage = (int) ($filters['per_page'] ?? 20);
        $perPage = max(1, min($perPage, 100));

        $query = Comment::query()
            ->with(['user', 'commentable'])
            ->orderByDesc('created_at');

        $this->applyFilters($query, $filters);

        return $query->paginate($perPage)->appends($filters);
    }

    public function listForPost(Post $post, array $filters = []): LengthAwarePaginator
    {
        $filters = array_merge($filters, [
            'commentable_type' => Post::class,
            'commentable_id' => $post->id,
            'status' => 'approved',
        ]);

        return $this->list($filters);
    }

    public function create(Post $post, ?User $user, array $data): Comment
    {
        $parentComment = null;

        if (! empty($data['parent_id'])) {
            /** @var Comment|null $parentComment */
            $parentComment = Comment::query()
                ->where('commentable_type', Post::class)
                ->where('commentable_id', $post->id)
                ->find($data['parent_id']);

            if (! $parentComment) {
                throw ValidationException::withMessages([
                    'parent_id' => __('Komentar induk tidak ditemukan.'),
                ]);
            }
        }

        $isApproved = ! $this->settings->comment_moderation;

        /** @var Comment $comment */
        $comment = $post->comments()->create([
            'user_id' => $user?->id,
            'parent_id' => $parentComment?->id,
            'guest_name' => $user ? null : Arr::get($data, 'guest_name'),
            'guest_email' => $user ? null : Arr::get($data, 'guest_email'),
            'content' => Arr::get($data, 'content'),
            'is_approved' => $isApproved,
            'is_featured' => false,
            'metadata' => Arr::get($data, 'metadata', []),
        ]);

        return $comment->fresh(['user', 'commentable']);
    }

    public function approve(Comment $comment, User $user): Comment
    {
        $comment->approve();

        return $comment->fresh(['user', 'commentable']);
    }

    public function delete(Comment $comment): void
    {
        $comment->delete();
    }

    protected function applyFilters(Builder $query, array $filters): void
    {
        if ($status = Arr::get($filters, 'status')) {
            if ($status === 'approved') {
                $query->where('is_approved', true);
            } elseif ($status === 'pending') {
                $query->where('is_approved', false);
            }
        }

        if ($commentableType = Arr::get($filters, 'commentable_type')) {
            $query->where('commentable_type', $commentableType);
        }

        if ($commentableId = Arr::get($filters, 'commentable_id')) {
            $query->where('commentable_id', $commentableId);
        }

        if ($userId = Arr::get($filters, 'user_id')) {
            $query->where('user_id', $userId);
        }

        if ($search = Arr::get($filters, 'search')) {
            $query->where('content', 'like', '%' . $search . '%');
        }
    }
}

<?php

namespace App\Jobs;

use App\Models\Comment;
use App\Models\Post;
use App\Notifications\CommentReceivedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class SendCommentNotification implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $tries = 3;

    public function __construct(
        protected Comment $comment,
    ) {
        $queue = config('queue.connections.'.config('queue.default').'.queue', 'default');
        $this->onQueue($queue);
    }

    public function handle(): void
    {
        $comment = $this->comment->fresh(['commentable.author', 'user', 'parent.user']);

        if (! $comment instanceof Comment || ! $comment->commentable instanceof Post) {
            return;
        }

        $recipients = $this->resolveRecipients($comment);

        if ($recipients->isEmpty()) {
            return;
        }

        $notification = new CommentReceivedNotification($comment);

        $recipients->each(function ($notifiable) use ($notification): void {
            $notifiable->notify($notification);
        });
    }

    protected function resolveRecipients(Comment $comment): Collection
    {
        $post = $comment->commentable;
        $author = $post->author;
        $parentAuthor = $comment->parent?->user;

        return collect([$author, $parentAuthor])
            ->filter()
            ->unique(fn ($user) => $user->getKey())
            ->reject(fn ($user) => $comment->user && $user->is($comment->user));
    }
}

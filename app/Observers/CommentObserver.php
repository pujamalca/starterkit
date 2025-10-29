<?php

namespace App\Observers;

use App\Mail\NewCommentNotification;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Facades\Mail;

class CommentObserver
{
    /**
     * Handle the Comment "created" event.
     */
    public function created(Comment $comment): void
    {
        // Only send notification for comments on posts
        if (!$comment->commentable instanceof Post) {
            return;
        }

        /** @var Post $post */
        $post = $comment->commentable;

        // Only send notification if post has an author and author has an email
        if (!$post->author || !$post->author->email) {
            return;
        }

        // Don't send notification if the comment author is the post author
        if ($comment->user_id === $post->author_id) {
            return;
        }

        // Send notification to post author
        Mail::to($post->author->email)->send(new NewCommentNotification($comment, $post));
    }

    /**
     * Handle the Comment "updated" event.
     */
    public function updated(Comment $comment): void
    {
        //
    }

    /**
     * Handle the Comment "deleted" event.
     */
    public function deleted(Comment $comment): void
    {
        //
    }

    /**
     * Handle the Comment "restored" event.
     */
    public function restored(Comment $comment): void
    {
        //
    }

    /**
     * Handle the Comment "force deleted" event.
     */
    public function forceDeleted(Comment $comment): void
    {
        //
    }
}

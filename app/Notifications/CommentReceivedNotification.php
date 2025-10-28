<?php

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CommentReceivedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected Comment $comment,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $post = $this->comment->commentable;
        $url = url('/posts/'.$post?->slug.'#comment-'.$this->comment->id);

        return (new MailMessage())
            ->subject(__('Komentar baru di ":title"', ['title' => $post?->title]))
            ->greeting(__('Halo :name!', ['name' => $notifiable->name ?? $notifiable->email]))
            ->line(__('Anda menerima komentar baru:'))
            ->line('"'.$this->comment->content.'"')
            ->action(__('Tinjau Komentar'), $url)
            ->line(__('Balas atau moderasi komentar langsung dari dashboard Anda.'));
    }

    public function toArray(object $notifiable): array
    {
        $post = $this->comment->commentable;

        return [
            'comment_id' => $this->comment->id,
            'post_id' => $post?->id,
            'post_title' => $post?->title,
            'content' => $this->comment->content,
        ];
    }
}


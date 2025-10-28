<?php

namespace App\Notifications;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PostPublishedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected Post $post,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = url('/posts/'.$this->post->slug);

        return (new MailMessage())
            ->subject(__('Post :title telah dipublikasikan', ['title' => $this->post->title]))
            ->greeting(__('Halo :name!', ['name' => $notifiable->name ?? $notifiable->email]))
            ->line(__('Konten Anda ":title" kini sudah tayang.', ['title' => $this->post->title]))
            ->action(__('Lihat Post'), $url)
            ->line(__('Pastikan untuk membagikannya dan pantau statistik performa post Anda.'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'post_id' => $this->post->id,
            'title' => $this->post->title,
            'slug' => $this->post->slug,
            'message' => __('Post ":title" telah dipublikasikan.', ['title' => $this->post->title]),
        ];
    }
}


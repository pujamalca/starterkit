<?php

namespace App\Mail;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewCommentNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Comment $comment,
        public Post $post
    ) {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Komentar Baru di: ' . $this->post->title,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $commenterName = $this->comment->user
            ? $this->comment->user->name
            : $this->comment->guest_name;

        return new Content(
            markdown: 'emails.new-comment',
            with: [
                'postTitle' => $this->post->title,
                'postUrl' => route('blog.show', $this->post->slug),
                'commenterName' => $commenterName,
                'commentContent' => $this->comment->content,
                'commentDate' => $this->comment->created_at->translatedFormat('d F Y, H:i'),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}

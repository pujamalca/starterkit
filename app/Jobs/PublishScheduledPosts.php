<?php

namespace App\Jobs;

use App\Models\Post;
use App\Services\PostService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class PublishScheduledPosts implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        protected ?Carbon $now = null,
    ) {
        $queue = config('queue.connections.'.config('queue.default').'.queue', 'default');
        $this->onQueue($queue);
    }

    public function handle(PostService $postService): int
    {
        $now = $this->now?->copy() ?? now();
        $processed = 0;

        Post::query()
            ->where('status', 'scheduled')
            ->whereNotNull('scheduled_at')
            ->where('scheduled_at', '<=', $now)
            ->orderBy('scheduled_at')
            ->chunkById(50, function ($posts) use (&$processed, $postService, $now): void {
                foreach ($posts as $post) {
                    $postService->update($post, [
                        'status' => 'published',
                        'published_at' => $post->published_at ?? $now,
                    ]);

                    $processed++;
                }
            });

        return $processed;
    }
}


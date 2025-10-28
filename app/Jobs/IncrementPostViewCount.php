<?php

namespace App\Jobs;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class IncrementPostViewCount implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        protected int $postId,
    ) {
        $this->onQueue('metrics');
    }

    public function handle(): void
    {
        Post::withoutTimestamps(function (): void {
            Post::query()
                ->whereKey($this->postId)
                ->increment('view_count');
        });
    }
}


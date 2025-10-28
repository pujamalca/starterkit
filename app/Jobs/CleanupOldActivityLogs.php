<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\Activitylog\Models\Activity;

class CleanupOldActivityLogs implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        protected int $days = 90,
    ) {
        $queue = config('queue.connections.'.config('queue.default').'.queue', 'default');
        $this->onQueue($queue);
    }

    public function handle(): int
    {
        $cutoff = now()->subDays(max(1, $this->days));
        $deleted = 0;

        Activity::query()
            ->where('created_at', '<', $cutoff)
            ->orderBy('id')
            ->chunkById(500, function (Collection $activities) use (&$deleted): void {
                $ids = $activities->pluck('id');
                $deleted += Activity::query()->whereKey($ids)->delete();
            });

        return $deleted;
    }
}


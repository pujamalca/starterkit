<?php

namespace App\Jobs;

use App\Services\DatabaseBackupService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DatabaseBackupJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        protected string $format = 'json',
    ) {
    }

    public function handle(DatabaseBackupService $service): void
    {
        $path = $service->createBackup($this->format);

        Log::info('Database backup created.', [
            'path' => storage_path("app/{$path}"),
            'format' => $this->format,
        ]);
    }
}

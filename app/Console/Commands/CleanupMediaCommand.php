<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'media:cleanup-orphans', description: 'Remove orphaned media records and missing files.')]
class CleanupMediaCommand extends Command
{
    protected $signature = 'media:cleanup-orphans {--dry-run : Only report affected media without deleting them}';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $action = $dryRun ? 'Scanning' : 'Cleaning';

        $this->line("{$action} orphaned media records...");

        $removed = 0;

        Media::query()
            ->orderBy('id')
            ->chunkById(200, function ($mediaItems) use (&$removed, $dryRun): void {
                foreach ($mediaItems as $media) {
                    if ($this->isOrphaned($media) || $this->fileMissing($media)) {
                        $removed++;

                        if (! $dryRun) {
                            $media->delete();
                        }

                        $this->line(sprintf(
                            '%s media #%d (%s)',
                            $dryRun ? 'Found' : 'Removed',
                            $media->id,
                            $media->file_name
                        ));
                    }
                }
            });

        $message = $dryRun
            ? "Identified {$removed} orphaned media record(s)."
            : "Removed {$removed} orphaned media record(s).";

        $this->info($message);

        return self::SUCCESS;
    }

    protected function isOrphaned(Media $media): bool
    {
        return ! $media->model;
    }

    protected function fileMissing(Media $media): bool
    {
        return ! rescue(
            fn () => Storage::disk($media->disk)->exists($media->getPath()),
            false,
            report: false
        );
    }
}


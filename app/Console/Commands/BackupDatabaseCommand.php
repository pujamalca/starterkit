<?php

namespace App\Console\Commands;

use App\Jobs\DatabaseBackupJob;
use App\Services\DatabaseBackupService;
use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Throwable;

#[AsCommand(name: 'system:backup', description: 'Buat cadangan database ke folder storage/app/backups.')]
class BackupDatabaseCommand extends Command
{
    protected $signature = 'system:backup {format=json : Format backup (json, csv, sql)} {--queue : Jalankan proses backup melalui antrean}';

    public function handle(DatabaseBackupService $service): int
    {
        $format = strtolower((string) $this->argument('format'));

        if (! in_array($format, DatabaseBackupService::FORMATS, true)) {
            $this->error('Format tidak valid. Gunakan: '.implode(', ', DatabaseBackupService::FORMATS).'.');

            return self::FAILURE;
        }

        if ($this->option('queue')) {
            DatabaseBackupJob::dispatch($format);

            $this->info(sprintf(
                'Backup %s dijadwalkan. File akan tersimpan di storage/app/backups.',
                strtoupper($format)
            ));

            return self::SUCCESS;
        }

        try {
            $relativePath = $service->createBackup($format);
            $fullPath = storage_path("app/{$relativePath}");

            $this->info("Backup {$format} selesai. File tersimpan di {$fullPath}");

            return self::SUCCESS;
        } catch (Throwable $throwable) {
            report($throwable);

            $this->error('Backup gagal: '.$throwable->getMessage());

            return self::FAILURE;
        }
    }
}

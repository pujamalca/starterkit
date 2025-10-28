<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'system:backup', description: 'Trigger application backup if the backup:run command is available.')]
class BackupDatabaseCommand extends Command
{
    protected $signature = 'system:backup {--only-db : Run database backup only} {--queue : Queue the backup command instead of running synchronously}';

    public function handle(): int
    {
        if (! array_key_exists('backup:run', Artisan::all())) {
            $this->warn('Perintah backup:run tidak tersedia. Pastikan paket spatie/laravel-backup terpasang.');

            return self::FAILURE;
        }

        $params = [];

        if ($this->option('only-db')) {
            $params['--only-db'] = true;
        }

        if ($this->option('queue')) {
            Artisan::queue('backup:run', $params);
            $this->info('Perintah backup:run telah dimasukkan ke dalam antrean.');

            return self::SUCCESS;
        }

        $status = Artisan::call('backup:run', $params);

        $this->line(Artisan::output());

        return $status === 0 ? self::SUCCESS : self::FAILURE;
    }
}


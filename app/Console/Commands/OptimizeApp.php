<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class OptimizeApp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:optimize';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Optimize the application (config, routes, events) - Skip view caching due to Filament';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->components->info('Optimizing application...');

        // Cache configuration
        $this->components->task('Caching configuration', function () {
            $this->call('config:cache');
            return true;
        });

        // Cache events
        $this->components->task('Caching events', function () {
            $this->call('event:cache');
            return true;
        });

        // Cache routes
        $this->components->task('Caching routes', function () {
            $this->call('route:cache');
            return true;
        });

        $this->components->info('Application optimized successfully!');
        $this->components->warn('Note: View caching skipped - Filament views cannot be cached');

        return Command::SUCCESS;
    }
}

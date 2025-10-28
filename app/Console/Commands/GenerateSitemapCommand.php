<?php

namespace App\Console\Commands;

use App\Jobs\GenerateSitemap;
use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'content:generate-sitemap', description: 'Generate the public sitemap.xml file.')]
class GenerateSitemapCommand extends Command
{
    protected $signature = 'content:generate-sitemap {--queue : Dispatch the job to the queue}';

    public function handle(): int
    {
        if ($this->option('queue')) {
            GenerateSitemap::dispatch();
            $this->info('Sitemap generation job dispatched.');

            return self::SUCCESS;
        }

        GenerateSitemap::dispatchSync();

        $this->info('Sitemap generated successfully.');

        return self::SUCCESS;
    }
}


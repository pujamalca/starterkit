<?php

namespace App\Console\Commands;

use App\Jobs\PublishScheduledPosts;
use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'content:publish-scheduled', description: 'Publish scheduled posts that are due.')]
class PublishScheduledPostsCommand extends Command
{
    protected $signature = 'content:publish-scheduled {--queue : Dispatch the job to the queue instead of running synchronously}';

    public function handle(): int
    {
        if ($this->option('queue')) {
            PublishScheduledPosts::dispatch();
            $this->info('Publish job dispatched.');

            return self::SUCCESS;
        }

        $processed = PublishScheduledPosts::dispatchSync();

        $this->info("Published {$processed} scheduled post(s).");

        return self::SUCCESS;
    }
}


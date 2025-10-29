<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;

class FixPublishedPostsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:fix-published';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix published posts that have null published_at dates';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Checking for published posts without published_at...');

        $posts = Post::where('status', 'published')
            ->whereNull('published_at')
            ->get();

        if ($posts->isEmpty()) {
            $this->info('No posts need fixing. All published posts have published_at dates.');
            return Command::SUCCESS;
        }

        $this->info("Found {$posts->count()} post(s) to fix.");

        $bar = $this->output->createProgressBar($posts->count());
        $bar->start();

        foreach ($posts as $post) {
            // Set published_at to created_at or now
            $post->published_at = $post->created_at ?? now();
            $post->saveQuietly(); // Save without triggering observers
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        $this->info("✓ Successfully fixed {$posts->count()} post(s).");

        return Command::SUCCESS;
    }
}

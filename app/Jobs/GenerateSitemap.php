<?php

namespace App\Jobs;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;

class GenerateSitemap implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function handle(): void
    {
        $entries = $this->collectEntries();
        $xml = $this->renderXml($entries);

        File::ensureDirectoryExists(public_path());
        File::put(public_path('sitemap.xml'), $xml);
    }

    protected function collectEntries(): array
    {
        $baseUrl = rtrim(config('app.url'), '/');

        $entries = [
            [
                'loc' => $baseUrl ?: url('/'),
                'lastmod' => now()->toAtomString(),
                'priority' => '1.0',
            ],
        ];

        Post::query()
            ->where('status', 'published')
            ->orderByDesc('published_at')
            ->limit(2000)
            ->get(['slug', 'updated_at', 'published_at', 'created_at'])
            ->each(function (Post $post) use (&$entries, $baseUrl): void {
                $entries[] = [
                    'loc' => $baseUrl.'/posts/'.$post->slug,
                    'lastmod' => optional($post->updated_at ?? $post->published_at ?? $post->created_at)->toAtomString(),
                    'priority' => '0.8',
                ];
            });

        return $entries;
    }

    protected function renderXml(array $entries): string
    {
        $lines = [
            '<?xml version="1.0" encoding="UTF-8"?>',
            '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">',
        ];

        foreach ($entries as $entry) {
            $lines[] = '  <url>';
            $lines[] = '    <loc>'.htmlspecialchars($entry['loc'], ENT_XML1).'</loc>';

            if (! empty($entry['lastmod'])) {
                $lines[] = '    <lastmod>'.htmlspecialchars($entry['lastmod'], ENT_XML1).'</lastmod>';
            }

            if (! empty($entry['priority'])) {
                $lines[] = '    <priority>'.$entry['priority'].'</priority>';
            }

            $lines[] = '  </url>';
        }

        $lines[] = '</urlset>';

        return implode(PHP_EOL, $lines).PHP_EOL;
    }
}


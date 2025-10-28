<?php

namespace App\Repositories;

use App\Models\Page;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class PageRepository
{
    public function findPublishedBySlug(string $slug): Page
    {
        $cacheKey = $this->cacheKey($slug);
        $ttl = now()->addSeconds((int) config('starterkit.cache.pages_ttl', 600));

        /** @var array<string, mixed> $payload */
        $payload = Cache::remember($cacheKey, $ttl, function () use ($slug) {
            /** @var Page $page */
            $page = Page::query()
                ->published()
                ->where('slug', $slug)
                ->firstOrFail();

            return [
                'attributes' => $page->getAttributes(),
                'relations' => $page->getRelations(),
            ];
        });

        $page = new Page($payload['attributes']);
        $page->exists = true;

        foreach ($payload['relations'] as $relation => $value) {
            $page->setRelation($relation, $value);
        }

        return $page;
    }

    public function forget(Page $page, ?string $originalSlug = null): void
    {
        Cache::forget($this->cacheKey($page->slug));

        if ($originalSlug && ! Str::of($originalSlug)->exactly($page->slug)) {
            Cache::forget($this->cacheKey($originalSlug));
        }
    }

    protected function cacheKey(string $slug): string
    {
        return sprintf('pages:published:%s', $slug);
    }
}


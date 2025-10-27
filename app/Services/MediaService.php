<?php

namespace App\Services;

class MediaService
{
    public function normalizePath(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        return ltrim((string) $path, '/');
    }

    public function normalizeMany(array $paths): array
    {
        return collect($paths)
            ->map(fn (mixed $path) => is_string($path) ? $this->normalizePath($path) : null)
            ->filter()
            ->values()
            ->all();
    }
}

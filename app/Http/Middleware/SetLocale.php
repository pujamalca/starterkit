<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Apply request locale before handling.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $this->resolveLocale($request);

        if ($locale !== null) {
            app()->setLocale($locale);
        }

        return $next($request);
    }

    protected function resolveLocale(Request $request): ?string
    {
        $candidates = array_filter([
            $request->query('locale'),
            $request->header('X-Locale'),
            $this->fromAcceptLanguage($request->header('Accept-Language')),
            data_get($request->user(), 'preferences.locale'),
        ]);

        if (empty($candidates)) {
            return null;
        }

        $supported = collect(array_filter(array_unique(array_merge(
            Arr::wrap(config('app.supported_locales')),
            [config('app.locale'), config('app.fallback_locale')]
        ))))
            ->map(fn ($value) => $this->normalizeLocale((string) $value))
            ->filter()
            ->values()
            ->all();

        foreach ($candidates as $candidate) {
            $normalized = $this->normalizeLocale($candidate);

            if ($normalized === null) {
                continue;
            }

            if (empty($supported) || in_array($normalized, $supported, true)) {
                return $normalized;
            }
        }

        return null;
    }

    protected function fromAcceptLanguage(?string $header): ?string
    {
        if (! $header) {
            return null;
        }

        $locale = Str::before($header, ';');
        $locale = Str::before($locale, ',');

        return $locale ?: null;
    }

    protected function normalizeLocale(string $locale): ?string
    {
        $locale = Str::slug(str_replace('_', '-', $locale), '-');

        if ($locale === '') {
            return null;
        }

        $parts = explode('-', $locale);
        $language = strtolower($parts[0] ?? '');
        $region = strtoupper($parts[1] ?? '');

        if ($language === '') {
            return null;
        }

        return $region ? sprintf('%s-%s', $language, $region) : $language;
    }
}


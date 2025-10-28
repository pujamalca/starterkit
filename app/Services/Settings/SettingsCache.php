<?php

namespace App\Services\Settings;

use App\Settings\GeneralSettings;
use Illuminate\Contracts\Cache\Repository as CacheRepository;

class SettingsCache
{
    public function __construct(
        protected CacheRepository $cache,
        protected GeneralSettings $generalSettings,
    ) {
    }

    public function general(): GeneralSettings
    {
        $ttl = now()->addSeconds((int) config('starterkit.cache.settings_ttl', 300));

        $payload = $this->cache->remember(
            $this->generalKey(),
            $ttl,
            fn () => $this->generalSettings->refresh()->toArray(),
        );

        return new GeneralSettings($payload);
    }

    public function flushGeneral(): void
    {
        $this->cache->forget($this->generalKey());
    }

    protected function generalKey(): string
    {
        return 'settings:general';
    }
}


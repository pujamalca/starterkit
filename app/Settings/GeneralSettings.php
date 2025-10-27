<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public string $site_name;

    public ?string $site_description;

    public ?string $site_logo;

    public ?string $site_favicon;

    public ?string $site_keywords;

    public bool $maintenance_mode;

    public int $posts_per_page;

    public bool $comment_moderation;

    public static function group(): string
    {
        return 'general';
    }
}


<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class LandingPageSettings extends Settings
{
    // Hero Section
    public string $hero_title;
    public string $hero_subtitle;
    public string $hero_description;
    public ?string $hero_image;
    public string $hero_cta_text;
    public string $hero_cta_url;
    public string $hero_secondary_cta_text;
    public string $hero_secondary_cta_url;

    // Features Section
    public bool $show_features;
    public string $features_title;
    public string $features_subtitle;
    public string $features;

    // Blog Section
    public bool $show_blog;
    public string $blog_title;
    public string $blog_subtitle;
    public int $blog_posts_count;

    // CTA Section
    public bool $show_cta;
    public string $cta_title;
    public string $cta_description;
    public string $cta_button_text;
    public string $cta_button_url;
    public string $cta_background_color;

    public static function group(): string
    {
        return 'landing_page';
    }
}

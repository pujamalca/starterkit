<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class LandingPageSettings extends Settings
{
    // Hero Section
    public string $hero_style;
    public string $hero_title;
    public string $hero_subtitle;
    public string $hero_description;
    public ?string $hero_image;
    public string $hero_buttons;

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

    // FAQ Section
    public bool $show_faq;
    public string $faq_title;
    public string $faq_subtitle;
    public string $faqs;

    // Navigation Menu
    public string $navigation_menus;
    public bool $show_search;

    // Login Page Panel
    public bool $login_show_panel = true;
    public ?string $login_panel_logo = null;
    public string $login_panel_heading = 'Welcome Back!';
    public string $login_panel_subheading = 'Sign in to access your admin dashboard and manage your application.';
    public ?string $login_panel_description = null;
    public string $login_panel_features = '[]';
    public ?string $login_panel_gradient_from = null;
    public ?string $login_panel_gradient_to = null;
    public bool $login_enable_registration = true;

    public static function group(): string
    {
        return 'landing_page';
    }

    public static function defaultLoginFeatures(): array
    {
        return [
            [
                'title' => 'Secure & Protected',
                'description' => 'Your data is encrypted and secure',
            ],
            [
                'title' => 'Easy Management',
                'description' => 'Intuitive admin interface',
            ],
            [
                'title' => '24/7 Access',
                'description' => 'Manage from anywhere, anytime',
            ],
        ];
    }
}

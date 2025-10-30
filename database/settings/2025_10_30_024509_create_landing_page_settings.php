<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // Hero Section
        $this->migrator->add('landing_page.hero_title', 'Welcome to Our Platform');
        $this->migrator->add('landing_page.hero_subtitle', 'Build amazing things with our starter kit');
        $this->migrator->add('landing_page.hero_description', 'A complete Laravel starter kit with Filament admin panel, blog, and more');
        $this->migrator->add('landing_page.hero_image', null);
        $this->migrator->add('landing_page.hero_cta_text', 'Get Started');
        $this->migrator->add('landing_page.hero_cta_url', '/blog');
        $this->migrator->add('landing_page.hero_secondary_cta_text', 'Learn More');
        $this->migrator->add('landing_page.hero_secondary_cta_url', '/pages/tentang-kami');

        // Features Section
        $this->migrator->add('landing_page.show_features', true);
        $this->migrator->add('landing_page.features_title', 'Our Features');
        $this->migrator->add('landing_page.features_subtitle', 'Everything you need to build your next project');
        $this->migrator->add('landing_page.features', json_encode([
            [
                'icon' => 'heroicon-o-rocket-launch',
                'title' => 'Fast & Modern',
                'description' => 'Built with latest technologies'
            ],
            [
                'icon' => 'heroicon-o-shield-check',
                'title' => 'Secure',
                'description' => 'Security best practices included'
            ],
            [
                'icon' => 'heroicon-o-puzzle-piece',
                'title' => 'Easy to Use',
                'description' => 'Simple and intuitive interface'
            ],
        ]));

        // Blog Section
        $this->migrator->add('landing_page.show_blog', true);
        $this->migrator->add('landing_page.blog_title', 'Latest Articles');
        $this->migrator->add('landing_page.blog_subtitle', 'Read our latest blog posts');
        $this->migrator->add('landing_page.blog_posts_count', 6);

        // CTA Section
        $this->migrator->add('landing_page.show_cta', true);
        $this->migrator->add('landing_page.cta_title', 'Ready to get started?');
        $this->migrator->add('landing_page.cta_description', 'Join thousands of users already using our platform');
        $this->migrator->add('landing_page.cta_button_text', 'Get Started Now');
        $this->migrator->add('landing_page.cta_button_url', '/admin');
        $this->migrator->add('landing_page.cta_background_color', '#3b82f6');
    }
};

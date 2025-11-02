<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // Navigation Menu
        $defaultMenus = [
            [
                'label' => 'Home',
                'url' => '/',
                'order' => 1,
                'type' => 'link',
                'show' => true,
            ],
            [
                'label' => 'Blog',
                'url' => '/blog',
                'order' => 2,
                'type' => 'blog_dropdown',
                'show' => true,
            ],
        ];

        $this->migrator->add('landing_page.navigation_menus', json_encode($defaultMenus));
    }

    public function down(): void
    {
        $this->migrator->deleteIfExists('landing_page.navigation_menus');
    }
};

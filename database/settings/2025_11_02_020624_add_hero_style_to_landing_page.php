<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // Add hero style field
        // Styles: 'image_right', 'full_background', 'centered_overlay'
        $this->migrator->add('landing_page.hero_style', 'image_right');
    }

    public function down(): void
    {
        $this->migrator->deleteIfExists('landing_page.hero_style');
    }
};

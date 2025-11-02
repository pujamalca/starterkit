<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // Add show_search field for navigation
        $this->migrator->add('landing_page.show_search', true);
    }

    public function down(): void
    {
        $this->migrator->deleteIfExists('landing_page.show_search');
    }
};

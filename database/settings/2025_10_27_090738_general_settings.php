<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.site_name', config('app.name', 'Starter Kit'));
        $this->migrator->add('general.site_description', 'Starter kit berbasis Laravel Filament.');
        $this->migrator->add('general.site_logo', null);
        $this->migrator->add('general.site_favicon', null);
        $this->migrator->add('general.site_keywords', null);
        $this->migrator->add('general.maintenance_mode', false);
        $this->migrator->add('general.posts_per_page', 10);
        $this->migrator->add('general.comment_moderation', true);
    }
};

<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('social.facebook_url', null);
        $this->migrator->add('social.twitter_url', null);
        $this->migrator->add('social.instagram_url', null);
        $this->migrator->add('social.linkedin_url', null);
        $this->migrator->add('social.youtube_url', null);
        $this->migrator->add('social.github_url', null);
    }
};

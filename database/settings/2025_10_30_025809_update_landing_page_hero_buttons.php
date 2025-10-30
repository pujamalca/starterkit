<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // Get existing values directly from repository
        $repository = app(\Spatie\LaravelSettings\SettingsRepositories\SettingsRepository::class);

        $cta_text = 'Get Started';
        $cta_url = '/blog';
        $secondary_cta_text = 'Learn More';
        $secondary_cta_url = '/pages/tentang-kami';

        // Try to get existing values if they exist
        if ($this->migrator->exists('landing_page.hero_cta_text')) {
            try {
                $cta_text = $repository->getPropertyPayload('landing_page', 'hero_cta_text') ?? $cta_text;
            } catch (\Exception $e) {
                // Use default if error
            }
        }

        if ($this->migrator->exists('landing_page.hero_cta_url')) {
            try {
                $cta_url = $repository->getPropertyPayload('landing_page', 'hero_cta_url') ?? $cta_url;
            } catch (\Exception $e) {
                // Use default if error
            }
        }

        if ($this->migrator->exists('landing_page.hero_secondary_cta_text')) {
            try {
                $secondary_cta_text = $repository->getPropertyPayload('landing_page', 'hero_secondary_cta_text') ?? $secondary_cta_text;
            } catch (\Exception $e) {
                // Use default if error
            }
        }

        if ($this->migrator->exists('landing_page.hero_secondary_cta_url')) {
            try {
                $secondary_cta_url = $repository->getPropertyPayload('landing_page', 'hero_secondary_cta_url') ?? $secondary_cta_url;
            } catch (\Exception $e) {
                // Use default if error
            }
        }

        // Convert to buttons array
        $buttons = [
            [
                'text' => $cta_text,
                'url' => $cta_url,
                'style' => 'primary',
            ],
            [
                'text' => $secondary_cta_text,
                'url' => $secondary_cta_url,
                'style' => 'secondary',
            ],
        ];

        // Add new field
        $this->migrator->add('landing_page.hero_buttons', json_encode($buttons));

        // Delete old fields if they exist
        $this->migrator->deleteIfExists('landing_page.hero_cta_text');
        $this->migrator->deleteIfExists('landing_page.hero_cta_url');
        $this->migrator->deleteIfExists('landing_page.hero_secondary_cta_text');
        $this->migrator->deleteIfExists('landing_page.hero_secondary_cta_url');
    }

    public function down(): void
    {
        // Get buttons from repository
        $repository = app(\Spatie\LaravelSettings\SettingsRepositories\SettingsRepository::class);
        $buttons = [];

        if ($this->migrator->exists('landing_page.hero_buttons')) {
            try {
                $buttonsJson = $repository->getPropertyPayload('landing_page', 'hero_buttons');
                $buttons = json_decode($buttonsJson, true) ?? [];
            } catch (\Exception $e) {
                // Use empty array if error
            }
        }

        // Restore old fields
        $this->migrator->add('landing_page.hero_cta_text', $buttons[0]['text'] ?? 'Get Started');
        $this->migrator->add('landing_page.hero_cta_url', $buttons[0]['url'] ?? '/blog');
        $this->migrator->add('landing_page.hero_secondary_cta_text', $buttons[1]['text'] ?? 'Learn More');
        $this->migrator->add('landing_page.hero_secondary_cta_url', $buttons[1]['url'] ?? '/pages/tentang-kami');

        // Delete new field
        $this->migrator->deleteIfExists('landing_page.hero_buttons');
    }
};

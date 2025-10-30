<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // Get existing values
        $cta_text = $this->migrator->get('landing_page.hero_cta_text', 'Get Started');
        $cta_url = $this->migrator->get('landing_page.hero_cta_url', '/blog');
        $secondary_cta_text = $this->migrator->get('landing_page.hero_secondary_cta_text', 'Learn More');
        $secondary_cta_url = $this->migrator->get('landing_page.hero_secondary_cta_url', '/pages/tentang-kami');

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

        // Delete old fields
        $this->migrator->delete('landing_page.hero_cta_text');
        $this->migrator->delete('landing_page.hero_cta_url');
        $this->migrator->delete('landing_page.hero_secondary_cta_text');
        $this->migrator->delete('landing_page.hero_secondary_cta_url');
    }

    public function down(): void
    {
        // Get buttons
        $buttons = json_decode($this->migrator->get('landing_page.hero_buttons', '[]'), true);

        // Restore old fields
        $this->migrator->add('landing_page.hero_cta_text', $buttons[0]['text'] ?? 'Get Started');
        $this->migrator->add('landing_page.hero_cta_url', $buttons[0]['url'] ?? '/blog');
        $this->migrator->add('landing_page.hero_secondary_cta_text', $buttons[1]['text'] ?? 'Learn More');
        $this->migrator->add('landing_page.hero_secondary_cta_url', $buttons[1]['url'] ?? '/pages/tentang-kami');

        // Delete new field
        $this->migrator->delete('landing_page.hero_buttons');
    }
};

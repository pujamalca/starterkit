<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Seed the application settings with sensible defaults.
     */
    public function run(): void
    {
        // General
        $this->seedSetting(
            group: 'general',
            name: 'site_name',
            displayName: 'Nama Situs',
            value: 'Laravel Filament Starter Kit',
            attributes: [
                'is_autoload' => true,
                'order' => 10,
            ],
        );

        $this->seedSetting(
            group: 'general',
            name: 'site_description',
            displayName: 'Deskripsi Situs',
            value: 'Starter kit konten modular berbasis Laravel + Filament.',
            attributes: [
                'is_autoload' => true,
                'order' => 20,
            ],
        );

        $this->seedSetting(
            group: 'general',
            name: 'site_logo',
            displayName: 'Logo',
            value: null,
            attributes: [
                'is_autoload' => true,
                'order' => 30,
            ],
        );

        $this->seedSetting(
            group: 'general',
            name: 'site_favicon',
            displayName: 'Favicon',
            value: null,
            attributes: [
                'is_autoload' => true,
                'order' => 40,
            ],
        );

        $this->seedSetting(
            group: 'general',
            name: 'site_keywords',
            displayName: 'Kata Kunci',
            value: 'laravel,filament,cms,starter-kit',
            attributes: [
                'is_autoload' => true,
                'order' => 50,
            ],
        );

        $this->seedSetting(
            group: 'general',
            name: 'maintenance_mode',
            displayName: 'Mode Pemeliharaan',
            value: false,
            attributes: [
                'is_autoload' => true,
                'order' => 60,
            ],
        );

        $this->seedSetting(
            group: 'general',
            name: 'posts_per_page',
            displayName: 'Posting per Halaman',
            value: 10,
            attributes: [
                'is_autoload' => true,
                'order' => 70,
            ],
        );

        $this->seedSetting(
            group: 'general',
            name: 'comment_moderation',
            displayName: 'Moderasi Komentar',
            value: true,
            attributes: [
                'is_autoload' => true,
                'order' => 80,
            ],
        );

        // Mail
        $this->seedSetting(
            group: 'mail',
            name: 'mail_from_address',
            displayName: 'Alamat Pengirim',
            value: 'admin@example.com',
            attributes: [
                'is_autoload' => true,
                'order' => 10,
            ],
        );

        $this->seedSetting(
            group: 'mail',
            name: 'mail_from_name',
            displayName: 'Nama Pengirim',
            value: 'Starter Kit',
            attributes: [
                'is_autoload' => true,
                'order' => 20,
            ],
        );

        $this->seedSetting(
            group: 'mail',
            name: 'mail_driver',
            displayName: 'Driver Mail',
            value: 'smtp',
            attributes: [
                'is_autoload' => true,
                'order' => 30,
            ],
        );

        $this->seedSetting(
            group: 'mail',
            name: 'smtp_host',
            displayName: 'SMTP Host',
            value: null,
            attributes: [
                'is_autoload' => true,
                'order' => 40,
            ],
        );

        $this->seedSetting(
            group: 'mail',
            name: 'smtp_port',
            displayName: 'SMTP Port',
            value: 587,
            attributes: [
                'is_autoload' => true,
                'order' => 50,
            ],
        );

        $this->seedSetting(
            group: 'mail',
            name: 'smtp_username',
            displayName: 'SMTP Username',
            value: null,
            attributes: [
                'is_autoload' => true,
                'order' => 60,
            ],
        );

        $this->seedSetting(
            group: 'mail',
            name: 'smtp_password',
            displayName: 'SMTP Password',
            value: null,
            attributes: [
                'is_autoload' => false,
                'order' => 70,
            ],
        );

        $this->seedSetting(
            group: 'mail',
            name: 'smtp_encryption',
            displayName: 'SMTP Encryption',
            value: 'tls',
            attributes: [
                'is_autoload' => true,
                'order' => 80,
            ],
        );

        // Social
        $this->seedSetting(
            group: 'social',
            name: 'facebook_url',
            displayName: 'Facebook',
            value: null,
            attributes: [
                'order' => 10,
            ],
        );

        $this->seedSetting(
            group: 'social',
            name: 'twitter_url',
            displayName: 'X / Twitter',
            value: null,
            attributes: [
                'order' => 20,
            ],
        );

        $this->seedSetting(
            group: 'social',
            name: 'instagram_url',
            displayName: 'Instagram',
            value: null,
            attributes: [
                'order' => 30,
            ],
        );

        $this->seedSetting(
            group: 'social',
            name: 'linkedin_url',
            displayName: 'LinkedIn',
            value: null,
            attributes: [
                'order' => 40,
            ],
        );

        $this->seedSetting(
            group: 'social',
            name: 'youtube_url',
            displayName: 'YouTube',
            value: null,
            attributes: [
                'order' => 50,
            ],
        );

        $this->seedSetting(
            group: 'social',
            name: 'github_url',
            displayName: 'GitHub',
            value: null,
            attributes: [
                'order' => 60,
            ],
        );
    }

    /**
     * Create a setting when missing and update its metadata otherwise.
     */
    protected function seedSetting(string $group, string $name, string $displayName, mixed $value, array $attributes = []): void
    {
        $metadata = array_filter([
            'display_name' => $displayName,
            'is_public' => $attributes['is_public'] ?? false,
            'is_autoload' => $attributes['is_autoload'] ?? false,
            'order' => $attributes['order'] ?? 0,
            'details' => $attributes['details'] ?? null,
        ], static fn ($val) => $val !== null);

        if (Setting::has($name, $group)) {
            Setting::query()
                ->where('group', $group)
                ->where('name', $name)
                ->update($metadata);

            return;
        }

        Setting::set($name, $value, $group, $metadata);
    }
}

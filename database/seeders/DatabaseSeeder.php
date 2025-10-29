<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call(RolePermissionSeeder::class);
        $this->call(SettingSeeder::class);

        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator Sistem',
                'username' => 'admin',
                'email_verified_at' => now(),
                'is_active' => true,
                'password' => Hash::make('password'),
            ]
        );

        if (! $admin->hasRole('Super Admin')) {
            $admin->assignRole('Super Admin');
        }

        $editor = User::firstOrCreate(
            ['email' => 'editor@example.com'],
            [
                'name' => 'Editor Konten',
                'username' => 'editor',
                'email_verified_at' => now(),
                'is_active' => true,
                'password' => Hash::make('password'),
            ]
        );

        if (! $editor->hasRole('Content Editor')) {
            $editor->assignRole('Content Editor');
        }

        // Gunakan IndonesianContentSeeder untuk konten berbahasa Indonesia
        // Atau gunakan ContentSeeder untuk konten random (English)
        $this->call(IndonesianContentSeeder::class);
        // $this->call(ContentSeeder::class);
    }
}

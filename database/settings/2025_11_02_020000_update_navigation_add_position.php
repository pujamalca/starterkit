<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // Get existing navigation menus
        $repository = app(\Spatie\LaravelSettings\SettingsRepositories\SettingsRepository::class);

        $existingMenus = [];
        if ($this->migrator->exists('landing_page.navigation_menus')) {
            try {
                $menusJson = $repository->getPropertyPayload('landing_page', 'navigation_menus');
                $existingMenus = json_decode($menusJson, true) ?? [];
            } catch (\Exception $e) {
                // Use empty array if error
            }
        }

        // Add position field to existing menus
        $updatedMenus = array_map(function($menu) {
            if (!isset($menu['position'])) {
                $menu['position'] = 'left'; // default position
            }
            return $menu;
        }, $existingMenus);

        // Update the navigation_menus setting
        if (!empty($updatedMenus)) {
            $this->migrator->update('landing_page.navigation_menus', function() use ($updatedMenus) {
                return json_encode($updatedMenus);
            });
        }
    }

    public function down(): void
    {
        // Get existing navigation menus
        $repository = app(\Spatie\LaravelSettings\SettingsRepositories\SettingsRepository::class);

        $existingMenus = [];
        if ($this->migrator->exists('landing_page.navigation_menus')) {
            try {
                $menusJson = $repository->getPropertyPayload('landing_page', 'navigation_menus');
                $existingMenus = json_decode($menusJson, true) ?? [];
            } catch (\Exception $e) {
                // Use empty array if error
            }
        }

        // Remove position field from menus
        $updatedMenus = array_map(function($menu) {
            unset($menu['position']);
            return $menu;
        }, $existingMenus);

        // Update the navigation_menus setting
        if (!empty($updatedMenus)) {
            $this->migrator->update('landing_page.navigation_menus', function() use ($updatedMenus) {
                return json_encode($updatedMenus);
            });
        }
    }
};

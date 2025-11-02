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

        // Remove 'order' field and add 'children' field
        $updatedMenus = array_map(function($menu) {
            // Remove order field (urutan otomatis dari posisi array)
            unset($menu['order']);

            // Add children field if not exists
            if (!isset($menu['children'])) {
                $menu['children'] = [];
            }

            return $menu;
        }, $existingMenus);

        // Update the navigation_menus setting
        if (!empty($updatedMenus)) {
            $this->migrator->update('landing_page.navigation_menus', function() use ($updatedMenus) {
                return json_encode(array_values($updatedMenus)); // array_values to re-index
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

        // Add back 'order' field and remove 'children'
        $updatedMenus = array_map(function($menu, $index) {
            $menu['order'] = $index + 1;
            unset($menu['children']);
            return $menu;
        }, $existingMenus, array_keys($existingMenus));

        // Update the navigation_menus setting
        if (!empty($updatedMenus)) {
            $this->migrator->update('landing_page.navigation_menus', function() use ($updatedMenus) {
                return json_encode(array_values($updatedMenus));
            });
        }
    }
};

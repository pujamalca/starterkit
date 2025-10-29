<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create new permissions
        $permissions = [
            [
                'name' => 'manage-pages',
                'slug' => 'manage-pages',
                'module' => 'content',
                'guard_name' => 'web',
                'description' => 'Mengelola halaman statis website.',
                'metadata' => json_encode(['label' => 'Manage Pages']),
            ],
            [
                'name' => 'manage-media',
                'slug' => 'manage-media',
                'module' => 'content',
                'guard_name' => 'web',
                'description' => 'Mengelola media library dan file upload.',
                'metadata' => json_encode(['label' => 'Manage Media']),
            ],
        ];

        foreach ($permissions as $permissionData) {
            $permission = Permission::firstOrNew(
                ['name' => $permissionData['name'], 'guard_name' => $permissionData['guard_name']]
            );

            if (!$permission->exists) {
                $permission->fill($permissionData);
                $permission->save();
            }
        }

        // Assign new permissions to Super Admin role
        $superAdmin = Role::where('name', 'Super Admin')->first();
        if ($superAdmin) {
            $superAdmin->givePermissionTo(['manage-pages', 'manage-media']);
        }

        // Assign manage-pages permission to Content Editor role
        $contentEditor = Role::where('name', 'Content Editor')->first();
        if ($contentEditor) {
            $contentEditor->givePermissionTo('manage-pages');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove permissions from roles
        $superAdmin = Role::where('name', 'Super Admin')->first();
        if ($superAdmin) {
            $superAdmin->revokePermissionTo(['manage-pages', 'manage-media']);
        }

        $contentEditor = Role::where('name', 'Content Editor')->first();
        if ($contentEditor) {
            $contentEditor->revokePermissionTo('manage-pages');
        }

        // Delete permissions
        Permission::whereIn('name', ['manage-pages', 'manage-media'])->delete();
    }
};

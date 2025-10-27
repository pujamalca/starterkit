<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissionDefinitions = [
            [
                'slug' => 'access-admin-panel',
                'label' => 'Access Admin Panel',
                'module' => 'system',
                'description' => 'Akses penuh ke panel admin.',
            ],
            [
                'slug' => 'manage-users',
                'label' => 'Manage Users',
                'module' => 'users',
                'description' => 'Mengelola data pengguna termasuk role & status aktif.',
            ],
            [
                'slug' => 'manage-roles',
                'label' => 'Manage Roles',
                'module' => 'users',
                'description' => 'Mengelola role dan permission.',
            ],
            [
                'slug' => 'access-settings',
                'label' => 'Access Settings',
                'module' => 'system',
                'description' => 'Akses halaman pengaturan aplikasi.',
            ],
            [
                'slug' => 'manage-posts',
                'label' => 'Manage Posts',
                'module' => 'content',
                'description' => 'Membuat dan mengelola postingan.',
            ],
            [
                'slug' => 'manage-categories',
                'label' => 'Manage Categories',
                'module' => 'content',
                'description' => 'Mengelola struktur kategori konten.',
            ],
            [
                'slug' => 'manage-tags',
                'label' => 'Manage Tags',
                'module' => 'content',
                'description' => 'Mengelola tag untuk klasifikasi konten.',
            ],
            [
                'slug' => 'manage-comments',
                'label' => 'Manage Comments',
                'module' => 'content',
                'description' => 'Moderasi dan penyuntingan komentar.',
            ],
            [
                'slug' => 'view-activity-log',
                'label' => 'View Activity Log',
                'module' => 'system',
                'description' => 'Melihat catatan aktivitas aplikasi.',
            ],
        ];

        $permissions = collect($permissionDefinitions)->mapWithKeys(function (array $attributes) {
            $permission = Permission::updateOrCreate(
                ['slug' => $attributes['slug']],
                [
                    'name' => $attributes['slug'],
                    'slug' => $attributes['slug'],
                    'module' => $attributes['module'],
                    'description' => $attributes['description'],
                    'guard_name' => 'web',
                    'metadata' => [
                        'label' => $attributes['label'],
                    ],
                ]
            );

            return [$permission->slug => $permission];
        });

        $superAdmin = Role::updateOrCreate(
            ['slug' => 'super-admin'],
            [
                'name' => 'Super Admin',
                'description' => 'Memiliki akses penuh ke seluruh fitur.',
                'guard_name' => 'web',
                'is_system' => true,
            ]
        );

        $superAdmin->syncPermissions($permissions->values());

        $contentEditor = Role::updateOrCreate(
            ['slug' => 'content-editor'],
            [
                'name' => 'Content Editor',
                'description' => 'Mengelola konten tanpa akses ke pengaturan kritikal.',
                'guard_name' => 'web',
            ]
        );

        $contentEditor->syncPermissions(
            $permissions->only([
                'access-admin-panel',
                'manage-posts',
                'manage-categories',
                'manage-tags',
                'manage-comments',
            ])->values()
        );
    }
}

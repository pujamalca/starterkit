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
        ];

        $permissions = collect($permissionDefinitions)->map(function (array $attributes) {
            return Permission::updateOrCreate(
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
        })->values();

        $superAdmin = Role::updateOrCreate(
            ['slug' => 'super-admin'],
            [
                'name' => 'Super Admin',
                'description' => 'Memiliki akses penuh ke seluruh fitur.',
                'guard_name' => 'web',
                'is_system' => true,
            ]
        );

        $superAdmin->syncPermissions($permissions);

        $contentEditor = Role::updateOrCreate(
            ['slug' => 'content-editor'],
            [
                'name' => 'Content Editor',
                'description' => 'Mengelola konten tanpa akses ke pengaturan kritikal.',
                'guard_name' => 'web',
            ]
        );

        $contentEditor->syncPermissions(
            $permissions->filter(fn (Permission $permission) => in_array($permission->slug, [
                'access-admin-panel',
            ], true))
        );
    }
}

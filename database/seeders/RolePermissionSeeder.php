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
                'name' => 'Manage Users',
                'slug' => 'manage-users',
                'module' => 'users',
                'description' => 'Mengelola data pengguna termasuk role & status aktif.',
            ],
            [
                'name' => 'Manage Roles',
                'slug' => 'manage-roles',
                'module' => 'users',
                'description' => 'Mengelola role dan permission.',
            ],
            [
                'name' => 'Access Settings',
                'slug' => 'access-settings',
                'module' => 'system',
                'description' => 'Akses halaman pengaturan aplikasi.',
            ],
        ];

        $permissions = collect($permissionDefinitions)->map(function (array $attributes) {
            return Permission::updateOrCreate(
                ['slug' => $attributes['slug']],
                array_merge($attributes, ['guard_name' => 'web'])
            );
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

        $superAdmin->syncPermissions($permissions);

        Role::updateOrCreate(
            ['slug' => 'content-editor'],
            [
                'name' => 'Content Editor',
                'description' => 'Mengelola konten tanpa akses ke pengaturan kritikal.',
                'guard_name' => 'web',
            ]
        );
    }
}


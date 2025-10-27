<?php

namespace App\Models;

use Illuminate\Support\Str;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    protected string $guard_name = 'web';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_system',
        'guard_name',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_system' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $role): void {
            if (blank($role->slug)) {
                $role->slug = Str::slug($role->name);
            }

            if (blank($role->guard_name)) {
                $role->guard_name = 'web';
            }
        });
    }
}

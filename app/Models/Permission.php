<?php

namespace App\Models;

use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    protected string $guard_name = 'web';

    protected $fillable = [
        'name',
        'slug',
        'module',
        'description',
        'guard_name',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $permission): void {
            if (blank($permission->slug)) {
                $permission->slug = Str::slug($permission->name);
            }

            if (blank($permission->guard_name)) {
                $permission->guard_name = 'web';
            }
        });
    }
}

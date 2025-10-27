<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Spatie\LaravelSettings\Models\SettingsProperty;

class Setting extends SettingsProperty
{
    use HasFactory;

    protected $table = 'settings';

    protected $fillable = [
        'group',
        'name',
        'display_name',
        'payload',
        'value',
        'type',
        'details',
        'is_public',
        'is_autoload',
        'locked',
        'order',
    ];

    protected $casts = [
        'details' => 'array',
        'is_public' => 'boolean',
        'is_autoload' => 'boolean',
        'locked' => 'boolean',
    ];

    protected $appends = [
        'casted_value',
    ];

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeByGroup($query, string $group)
    {
        return $query->where('group', $group);
    }

    protected function castedValue(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->castPayload($this->payload),
            set: fn ($value) => $this->preparePayload($value),
        );
    }

    protected function castPayload(mixed $payload): mixed
    {
        if (is_null($payload)) {
            return null;
        }

        $decoded = json_decode($payload, true);

        return match ($this->type) {
            'boolean' => (bool) $decoded,
            'number' => (float) $decoded,
            'integer' => (int) $decoded,
            'json' => $decoded,
            'array' => Arr::wrap($decoded),
            default => $decoded,
        };
    }

    protected function preparePayload(mixed $value): array
    {
        $this->value = is_scalar($value) ? (string) $value : json_encode($value);

        return ['payload' => json_encode($value)];
    }

    public static function get(string $key, mixed $default = null, string $group = 'general'): mixed
    {
        $setting = static::query()->where('group', $group)->where('name', $key)->first();

        if (! $setting) {
            return $default;
        }

        if (! $setting->is_autoload) {
            return $setting->casted_value ?? $default;
        }

        return Cache::rememberForever(self::cacheKey($group, $key), fn () => $setting->casted_value ?? $default);
    }

    public static function set(string $key, mixed $value, string $group = 'general', array $attributes = []): static
    {
        $payload = json_encode($value);
        $stringValue = is_scalar($value) ? (string) $value : json_encode($value);

        $setting = static::updateOrCreate(
            ['group' => $group, 'name' => $key],
            array_merge([
                'payload' => $payload,
                'value' => $stringValue,
                'type' => $attributes['type'] ?? static::inferType($value),
            ], $attributes),
        );

        Cache::forget(self::cacheKey($group, $key));

        return $setting;
    }

    public static function has(string $key, string $group = 'general'): bool
    {
        return static::query()->where('group', $group)->where('name', $key)->exists();
    }

    public static function forget(string $key, string $group = 'general'): void
    {
        static::query()->where('group', $group)->where('name', $key)->delete();
        Cache::forget(self::cacheKey($group, $key));
    }

    public static function getAllByGroup(string $group): array
    {
        return static::query()->where('group', $group)
            ->orderBy('order')
            ->get()
            ->mapWithKeys(fn (self $setting) => [$setting->name => $setting->casted_value])
            ->toArray();
    }

    protected static function cacheKey(string $group, string $key): string
    {
        return "settings.{$group}.{$key}";
    }

    protected static function inferType(mixed $value): string
    {
        return match (true) {
            is_bool($value) => 'boolean',
            is_int($value) => 'integer',
            is_float($value) => 'number',
            is_array($value) => 'json',
            default => 'text',
        };
    }
}

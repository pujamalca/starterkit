<?php

namespace App\Services\Doctor;

use App\Models\Comment;
use App\Models\Page;
use App\Models\Post;
use App\Models\User;
use Composer\InstalledVersions;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class DoctorService
{
    public function run(): array
    {
        return [
            'timestamp' => now(),
            'checks' => [
                'database' => $this->checkDatabase(),
                'cache' => $this->checkCache(),
                'storage' => $this->checkStorage(),
                'queue' => $this->checkQueue(),
            ],
            'analytics' => $this->analytics(),
            'versions' => $this->versions(),
        ];
    }

    protected function checkDatabase(): array
    {
        $connection = Config::get('database.default', 'default');

        $startedAt = microtime(true);

        try {
            DB::connection()->getPdo();

            $latency = round((microtime(true) - $startedAt) * 1000, 2);

            return [
                'status' => 'ok',
                'connection' => $connection,
                'latency_ms' => $latency,
                'database' => DB::connection()->getDatabaseName(),
            ];
        } catch (Throwable $throwable) {
            return [
                'status' => 'error',
                'connection' => $connection,
                'message' => $throwable->getMessage(),
            ];
        }
    }

    protected function checkCache(): array
    {
        $store = Config::get('cache.default', 'file');
        $key = 'doctor-check-'.Str::uuid();
        $value = Str::random(12);

        try {
            $startedAt = microtime(true);
            Cache::store($store)->put($key, $value, 10);
            $retrieved = Cache::store($store)->get($key);
            Cache::store($store)->forget($key);
            $latency = round((microtime(true) - $startedAt) * 1000, 2);

            if ($retrieved !== $value) {
                return [
                    'status' => 'warning',
                    'store' => $store,
                    'latency_ms' => $latency,
                    'message' => 'Cache store returned unexpected value.',
                ];
            }

            return [
                'status' => 'ok',
                'store' => $store,
                'latency_ms' => $latency,
            ];
        } catch (Throwable $throwable) {
            return [
                'status' => 'error',
                'store' => $store,
                'message' => $throwable->getMessage(),
            ];
        }
    }

    protected function checkStorage(): array
    {
        $disk = Config::get('filesystems.default', 'local');
        $path = 'doctor/check/'.Str::uuid().'.txt';

        try {
            $startedAt = microtime(true);
            Storage::disk($disk)->put($path, 'doctor-ok');
            $exists = Storage::disk($disk)->exists($path);
            Storage::disk($disk)->delete($path);
            $latency = round((microtime(true) - $startedAt) * 1000, 2);

            if (! $exists) {
                return [
                    'status' => 'warning',
                    'disk' => $disk,
                    'latency_ms' => $latency,
                    'message' => 'Tidak dapat memverifikasi file di storage disk.',
                ];
            }

            return [
                'status' => 'ok',
                'disk' => $disk,
                'latency_ms' => $latency,
            ];
        } catch (Throwable $throwable) {
            return [
                'status' => 'error',
                'disk' => $disk,
                'message' => $throwable->getMessage(),
            ];
        }
    }

    protected function checkQueue(): array
    {
        $connection = Config::get('queue.default', 'sync');
        $config = Config::get("queue.connections.{$connection}", []);
        $driver = Arr::get($config, 'driver', 'unknown');
        $queueName = Arr::get($config, 'queue');

        try {
            $queueConnection = Queue::connection($connection);
            $pending = null;

            if (method_exists($queueConnection, 'size')) {
                try {
                    $pending = $queueConnection->size($queueName);
                } catch (Throwable) {
                    $pending = null;
                }
            }

            return [
                'status' => 'ok',
                'connection' => $connection,
                'driver' => $driver,
                'queue' => $queueName,
                'pending_jobs' => $pending,
            ];
        } catch (Throwable $throwable) {
            return [
                'status' => 'error',
                'connection' => $connection,
                'driver' => $driver,
                'queue' => $queueName,
                'message' => $throwable->getMessage(),
            ];
        }
    }

    protected function analytics(): array
    {
        $sevenDaysAgo = now()->subDays(7);

        return [
            'posts_total' => Post::count(),
            'posts_published' => Post::published()->count(),
            'posts_last_7_days' => Post::where('created_at', '>=', $sevenDaysAgo)->count(),
            'pages_total' => Page::count(),
            'pages_published' => Page::published()->count(),
            'users_total' => User::count(),
            'users_active' => User::where('is_active', true)->count(),
            'comments_total' => Comment::count(),
            'comments_pending' => Comment::where('is_approved', false)->count(),
        ];
    }

    protected function versions(): array
    {
        return array_filter([
            'php' => PHP_VERSION,
            'laravel' => app()->version(),
            'filament' => $this->versionFor('filament/filament'),
            'starterkit' => $this->versionFor('laravel/laravel'),
            'spatie_permission' => $this->versionFor('spatie/laravel-permission'),
        ]);
    }

    protected function versionFor(string $package): ?string
    {
        if (! class_exists(InstalledVersions::class) || ! InstalledVersions::isInstalled($package)) {
            return null;
        }

        return InstalledVersions::getPrettyVersion($package);
    }
}


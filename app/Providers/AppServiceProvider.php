<?php

namespace App\Providers;

use App\Models\Activity;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (! app()->runningInConsole()) {
            $request = request();

            if ($request) {
                $storageUrl = rtrim($request->getSchemeAndHttpHost(), '/') . '/storage';
                config()->set('filesystems.disks.public.url', $storageUrl);
            }
        }

        Activity::saving(function (Activity $activity): void {
            if (app()->runningInConsole()) {
                return;
            }

            $request = request();

            if (! $request) {
                return;
            }

            $activity->ip_address ??= $request->ip();
            $activity->user_agent ??= $request->userAgent();
            $activity->url ??= URL::full();
            $activity->method ??= $request->method();
        });
    }
}

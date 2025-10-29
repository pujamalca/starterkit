<?php

namespace App\Providers;

use App\Filament\Admin\Widgets\AnalyticsTrendsChart;
use App\Filament\Admin\Widgets\DoctorLatencyChart;
use App\Models\Activity;
use App\Models\Page;
use App\Models\Post;
use App\Observers\PostObserver;
use App\Repositories\PageRepository;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

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
        // Register observers
        Post::observe(PostObserver::class);

        RateLimiter::for('public-content', function (Request $request) {
            return [
                Limit::perMinute((int) config('starterkit.rate_limit.public', 120))
                    ->by($request->ip()),
            ];
        });

        RateLimiter::for('content-write', function (Request $request) {
            $identifier = $request->user()?->getAuthIdentifier()
                ? sprintf('user:%s', $request->user()->getAuthIdentifier())
                : sprintf('ip:%s', $request->ip());

            return [
                Limit::perMinute((int) config('starterkit.rate_limit.content_write', 30))
                    ->by($identifier),
            ];
        });

        RateLimiter::for('comments', function (Request $request) {
            return [
                Limit::perMinute((int) config('starterkit.rate_limit.comments', 20))
                    ->by($request->ip()),
            ];
        });

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

        Page::saved(function (Page $page): void {
            /** @var PageRepository $repository */
            $repository = app(PageRepository::class);
            $originalSlug = $page->getOriginal('slug');

            $repository->forget($page, $originalSlug);
        });

        Page::deleted(function (Page $page): void {
            /** @var PageRepository $repository */
            $repository = app(PageRepository::class);
            $repository->forget($page, $page->getOriginal('slug'));
        });

        Page::restored(function (Page $page): void {
            /** @var PageRepository $repository */
            $repository = app(PageRepository::class);
            $repository->forget($page, $page->getOriginal('slug'));
        });

        Livewire::component('filament.admin.widgets.doctor-latency-chart', DoctorLatencyChart::class);
        Livewire::component('filament.admin.widgets.analytics-trends-chart', AnalyticsTrendsChart::class);
    }
}

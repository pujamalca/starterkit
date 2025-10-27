<?php

namespace App\Http\Middleware;

use App\Settings\GeneralSettings;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var GeneralSettings $settings */
        $settings = app(GeneralSettings::class);

        if (! $settings->maintenance_mode) {
            return $next($request);
        }

        if ($this->shouldBypass($request)) {
            return $next($request);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => __('Server sedang dalam mode pemeliharaan. Silakan coba lagi nanti.'),
            ], 503);
        }

        return response()->view('maintenance', [
            'brandName' => $settings->site_name ?? config('app.name', 'Starter Kit'),
        ], 503);
    }

    protected function shouldBypass(Request $request): bool
    {
        $routeName = $request->route()?->getName();

        if ($routeName && str_starts_with($routeName, 'filament.')) {
            return true;
        }

        if (str_starts_with($request->path(), 'admin')) {
            return true;
        }

        if ($routeName && str_starts_with($routeName, 'login')) {
            return true;
        }

        if (str_starts_with($request->path(), 'login') || str_starts_with($request->path(), 'register') || str_starts_with($request->path(), 'password')) {
            return true;
        }

        if (str_starts_with($request->path(), 'livewire') || str_starts_with($request->path(), 'broadcasting')) {
            return true;
        }

        if ($request->expectsJson() || $request->ajax()) {
            return true;
        }

        return $request->user()?->can('access-admin-panel') ?? false;
    }
}

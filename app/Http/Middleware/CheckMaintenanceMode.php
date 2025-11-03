<?php

namespace App\Http\Middleware;

use App\Services\Settings\SettingsCache;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    public function __construct(
        protected SettingsCache $settingsCache,
    ) {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $settings = $this->settingsCache->general();

        if (! $settings->maintenance_mode) {
            return $next($request);
        }

        if ($this->shouldBypass($request)) {
            return $next($request);
        }

        $payload = [
            'message' => __('Server sedang dalam mode pemeliharaan. Silakan coba lagi nanti.'),
        ];

        if ($request->expectsJson()) {
            return response()->json($payload, 503);
        }

        return response()->view('maintenance', array_merge($payload, [
            'brandName' => $settings->site_name ?? config('app.name', 'Starter Kit'),
        ]), 503);
    }

    protected function shouldBypass(Request $request): bool
    {
        $routeName = $request->route()?->getName();

        if ($routeName && in_array($routeName, $this->allowedRouteNames(), true)) {
            return true;
        }

        if ($this->matchesAllowedPath($request)) {
            return true;
        }

        return $request->user()?->can('access-admin-panel') ?? false;
    }

    protected function allowedRouteNames(): array
    {
        return [
            'login',
            'logout',
            'password.request',
            'password.email',
            'password.reset',
            'password.update',
            'verification.notice',
            'verification.verify',
            'verification.resend',
            'filament.admin.auth.login',
            'filament.admin.auth.password.request',
            'filament.admin.auth.password.reset',
        ];
    }

    protected function matchesAllowedPath(Request $request): bool
    {
        $allowed = [
            'admin/login',
            'admin/login/*',
            'admin/password/*',
            'admin/forgot-password',
            'admin/reset-password/*',
            'livewire/message/filament.admin.pages.auth.login',
            'livewire/message/filament.admin.pages.auth.login/*',
        ];

        foreach ($allowed as $pattern) {
            if ($request->is($pattern)) {
                return true;
            }
        }

        if ($request->is('livewire/*') && str_contains($request->path(), 'filament')) {
            return true;
        }

        return false;
    }
}

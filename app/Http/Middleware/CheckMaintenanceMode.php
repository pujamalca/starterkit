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

        if ($this->isAdminRequest($request)) {
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

    protected function isAdminRequest(Request $request): bool
    {
        return str_starts_with($request->path(), 'admin');
    }
}

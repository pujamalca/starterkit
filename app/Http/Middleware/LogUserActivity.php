<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogUserActivity
{
    /**
     * Record mutating API requests for auditing.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $user = $request->user();
        $method = strtoupper($request->getMethod());

        if ($user && $request->is('api/*') && in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'], true)) {
            rescue(function () use ($user, $request, $response, $method) {
                activity('api')
                    ->causedBy($user)
                    ->withProperties([
                        'method' => $method,
                        'path' => $request->path(),
                        'ip' => $request->ip(),
                        'user_agent' => substr((string) $request->userAgent(), 0, 255),
                        'status' => $response->getStatusCode(),
                    ])
                    ->log('api_request');
            }, report: false);
        }

        return $response;
    }
}


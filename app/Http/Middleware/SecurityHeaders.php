<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // X-Frame-Options: Prevent clickjacking attacks
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // X-Content-Type-Options: Prevent MIME-sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // X-XSS-Protection: Enable XSS filtering
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Referrer-Policy: Control referrer information
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Strict-Transport-Security: Enforce HTTPS (only in production)
        if (app()->environment('production')) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        // Content-Security-Policy: Prevent XSS and other code injection attacks
        $csp = config('security.csp', "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline'; img-src 'self' data: https:; font-src 'self' data:; connect-src 'self'");
        $response->headers->set('Content-Security-Policy', $csp);

        // Permissions-Policy: Control browser features
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

        return $response;
    }
}

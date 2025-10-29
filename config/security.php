<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Content Security Policy (CSP)
    |--------------------------------------------------------------------------
    |
    | Define the Content Security Policy for your application.
    | This helps prevent XSS and other code injection attacks.
    |
    */

    'csp' => env('CSP_POLICY', implode('; ', [
        "default-src 'self'",
        "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.tailwindcss.com",
        "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com",
        "img-src 'self' data: https: blob:",
        "font-src 'self' data: https://fonts.gstatic.com",
        "connect-src 'self'",
        "frame-ancestors 'self'",
        "base-uri 'self'",
        "form-action 'self'",
    ])),

    /*
    |--------------------------------------------------------------------------
    | Trusted Proxies
    |--------------------------------------------------------------------------
    |
    | Define trusted proxy servers for your application.
    |
    */

    'trusted_proxies' => env('TRUSTED_PROXIES', '*'),

    /*
    |--------------------------------------------------------------------------
    | HSTS Max Age
    |--------------------------------------------------------------------------
    |
    | HTTP Strict Transport Security max age in seconds.
    |
    */

    'hsts_max_age' => env('HSTS_MAX_AGE', 31536000),

];

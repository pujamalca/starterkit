<?php

return [
    'cache' => [
        'pages_ttl' => env('STARTERKIT_CACHE_PAGES_TTL', 600),
        'settings_ttl' => env('STARTERKIT_CACHE_SETTINGS_TTL', 300),
    ],
    'rate_limit' => [
        'public' => env('STARTERKIT_RATE_LIMIT_PUBLIC', 120),
        'content_write' => env('STARTERKIT_RATE_LIMIT_CONTENT_WRITE', 30),
        'comments' => env('STARTERKIT_RATE_LIMIT_COMMENTS', 20),
    ],
];

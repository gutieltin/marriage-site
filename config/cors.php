<?php

return [
    'paths' => ['api/*'],
    'allowed_methods' => ['*'],
    // Comma-separated list in .env, e.g. CORS_ALLOWED_ORIGINS=https://yourwedding.com
    'allowed_origins' => array_filter(array_map('trim', explode(',', env('CORS_ALLOWED_ORIGINS', '*')))),
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];

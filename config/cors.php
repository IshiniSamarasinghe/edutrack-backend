<?php

return [
'paths' => ['sanctum/csrf-cookie', 'login', 'logout', 'register', 'user', 'api/*'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'http://localhost:3000',
        'http://127.0.0.1:3000',
    ],
    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,

    // very important when using cookies
    'supports_credentials' => true,
];
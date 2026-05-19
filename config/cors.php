<?php

return [

    'paths' => [
        'api/*',
        'sanctum/csrf-cookie',
    ],

    'allowed_methods' => ['*'],

    'allowed_origins' => array_filter([
        env('FRONTEND_URL', 'https://ekzamen-rabota.vercel.app'),
        env('FRONTEND_URL_LOCAL'),
        env('FRONTEND_URL_LOCAL_127'),
    ]),

    'allowed_origins_patterns' => [
        'https://*.vercel.app',
    ],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 86400,

    'supports_credentials' => true,

];
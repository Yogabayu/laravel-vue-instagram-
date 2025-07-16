<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'http://localhost:8000',
        'https://localhost:8000',
        env('APP_URL'), // Pastikan APP_URL di .env Anda sudah benar jika digunakan untuk IP internal
    ],

    'allowed_origins_patterns' => [
        '/^https?:\/\/192\.168\.70\.\d+$/',
        '/^https?:\/\/192\.168\.72\.\d+$/',
        '/^https?:\/\/192\.168\.73\.\d+$/',
        '/^https?:\/\/192\.168\.80\.\d+$/',
    ],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,

];

<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Configure which paths and domains are allowed to make cross-origin
    | requests to your Laravel app. These settings are essential for
    | frontend-backend setups like Vue + Laravel.
    |
    */

    'paths' => ['api/*', 'auth/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [env('FRONTEND_URL', 'https://tapah-homebased.vercel.app')],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    // IMPORTANT: must be true for cookies/session to work
    'supports_credentials' => true,

];

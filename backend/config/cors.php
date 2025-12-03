<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    */

    // IMPORTANT: Must list all endpoints your frontend hits
    'paths' => [
        'api/*',
        'auth/*',
        'sanctum/csrf-cookie',
        'storage/*',                     // <-- REQUIRED for images served from /storage
    ],

    'allowed_methods' => ['*'],

    // Allow only your production frontend + local dev
    'allowed_origins' => [
        'https://tapah-homebased.vercel.app',
        'http://localhost:5173',
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    // Must be false unless you are using cookies/sessions from frontend
    'supports_credentials' => false,
];

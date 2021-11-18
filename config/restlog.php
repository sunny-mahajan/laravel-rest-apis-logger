<?php

return [
    'driver' => env('REST_LOGS_DRIVER', 'file'),

    'filename' => env('REST_LOGS_FILENAME_FORMAT', 'rest-{Y-m-d}.log'),

    'exclude' => [
        'password', 'password_confirmation', 'new_password', 'old_password',
    ],

    'client' => env('REDIS_CLIENT', 'predis'), 
    'port' => env('REDIS_PORT', '6379'),
    'password' => env('REDIS_PASSWORD', null),
    'host' => env('REDIS_HOST', '127.0.0.1'),

];

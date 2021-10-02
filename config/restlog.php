<?php

return [
    'driver' => env('REST_LOGS_DRIVER', 'file'),

    'filename' => env('REST_LOGS_FILENAME_FORMAT', 'rest-{Y-m-d}.log'),

    'exclude' => [
        'password', 'password_confirmation', 'new_password', 'old_password',
    ],
];

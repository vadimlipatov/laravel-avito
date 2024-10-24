<?php

return [
    // "sms.ru", "array"
    'driver' => env('SMS_DRIVER', 'sms.ru'),

    'drivers' => [
        'sms.ru' => [
            'app_id' => env('SMS_RU_API_ID'),
            'url' => env('SMS_RU_URL'),
        ],
    ],
];

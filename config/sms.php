<?php

// Drivers: sms.ru | array

return [
    'driver' => env('SMS_DRIVER', 'sms.ru'),

    'drivers' => [
        'sms.ru' => [
            'api_id' => env('SMS_RU_API_ID'),
            'url' => env('SMS_RU_URL'),
        ],
    ],

];

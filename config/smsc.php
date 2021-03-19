<?php

return [

    /*
    |--------------------------------------------------------------------------
    | SMS-center (SMSC.ru) settings and credentials
    |--------------------------------------------------------------------------
    */

    'api_url' => 'https://smsc.ru/sys/send.php',

    'api_login' => env('SMSC_LOGIN'),

    'api_password' => env('SMSC_PASSWORD'),

    'sender_name' => env('SMSC_SENDER_NAME'),

    'fake_send' => env('SMSC_FAKE_SEND', true),

    'code_size' => env('SMSC_CODE_SIZE', 6),

    'code_lifetime' => env('SMSC_CODE_LIFETIME', 120),

    'token_lifetime' => env('SMSC_TOKEN_LIFETIME', 600),

    'queue_connection' => env('SMSC_QUEUE_CONNECTION'),

    'throttling_limit' => env('SMSC_THROTTLING_LIMIT', 2),

];

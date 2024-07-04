<?php

return [

    
    'default' => env('MAIL_MAILER', 'smtp'),

    'mailers' => [
        'smtp' => [
            'transport' => 'smtp',
            'host' => 'smtp.gmail.com',
            'port' => 587,
            'encryption' => 'tls',
            'username' => 'vanphong150703@gmail.com',
            'password' => 'kmmoolmdnqjxrkyz',
            'timeout' => null,
            'auth_mode' => null,
        ],
    ],

    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'example@mail.com'),
        'name' => env('MAIL_FROM_NAME', 'Example'),
    ],

];

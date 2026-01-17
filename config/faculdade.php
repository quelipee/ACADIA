<?php
    return [
        'base_login' => env('FACUL_BASE_URL'),

        'credentials' => [
            'login' => env('APP_UNINTER_LOGIN'),
            'password' => env('APP_UNINTER_PASSWORD')
        ],

        'http' => [
            'timeout' => 10,
            'retry' => 3
        ],

        // TODO AQUI TENHO QUE COLOCAR OS ENDPOINTS
    ];
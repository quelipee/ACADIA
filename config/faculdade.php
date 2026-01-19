<?php
    return [
        'base_urls' => [
            'login' => env('FACUL_BASE_LOGIN_URL'),
            'base_url' => env('FACUL_BASE_URL'),
        ],

        'credentials' => [
            'login' => env('APP_UNINTER_LOGIN'),
            'password' => env('APP_UNINTER_PASSWORD')
        ],

        'endpoints' => [
        'list_graduation' => 'ava/sistema/Curso/0/EscolaUsuario/true/?emCurso=true',
        'atividades' => [
            'listar'   => '/atividades',
            'detalhe'  => '/atividades/{id}',
            'responder'=> '/atividades/{id}/responder',
            ],
        ],

        'http' => [
            'timeout' => 10,
            'retry' => 3
        ],

        // TODO AQUI TENHO QUE COLOCAR OS ENDPOINTS
    ];
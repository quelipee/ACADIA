<?php
    return [
        'base_urls' => [
            'login' => env('FACUL_BASE_LOGIN_URL'),
            'api' => env('FACUL_BASE_URL'),
        ],

        'credentials' => [
            'login' => env('APP_UNINTER_LOGIN'),
            'password' => env('APP_UNINTER_PASSWORD')
        ],

        'endpoints' => [
        'list_graduation' => 'ava/sistema/Curso/0/EscolaUsuario/true/?emCurso=true',
        'list_materials' => 'ava/ava/SalaVirtual/{idCourse}/CursoUsuarioPermissao/true/',
        'list_activities' => 'ava/bqs/AvaliacaoUsuario/1/paginacao/true?numRegistros=25&filtro=&ordenacao=&idSalaVirtual={idSalaVirtual}&idSalaVirtualOferta={idSalaVirtualOferta}'
        ],

        'http' => [
            'timeout' => 10,
            'retry' => 3
        ],

        // TODO AQUI TENHO QUE COLOCAR OS ENDPOINTS
    ];
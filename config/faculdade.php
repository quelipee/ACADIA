<?php
    return [
        'base_urls' => [
            'login' => env('FACUL_BASE_LOGIN_URL'),
            'api' => env('FACUL_BASE_URL'),
            'openrouter_base_url' => env('OPENROUTER_BASE_URL')
        ],

        'credentials' => [
            'login' => env('APP_UNINTER_LOGIN'),
            'password' => env('APP_UNINTER_PASSWORD')
        ],

        'endpoints' => [
            'list_graduation' => 'ava/sistema/Curso/0/EscolaUsuario/true/?emCurso=true',
            'list_subjects' => 'ava/ava/SalaVirtual/{idCourse}/CursoUsuarioPermissao/true',
            'list_activities' => 'ava/bqs/AvaliacaoUsuario/1/paginacao/true',
            'list_proof' => 'ava/bqs/avaliacaoUsuario/false/vigenteProvas',
            'list_questions' => 'ava/bqs/AvaliacaoUsuarioHistorico/{id}/Avaliacao?autorizacao=',
            'list_questionsProof' => 'ava/bqs/AvaliacaoUsuarioHistorico/{idAvaliacaoUsuario}/Token/{token}',
            'confirm_start_assessment' => 'ava/bqs/AvaliacaoUsuario/0/tentativa/{try}',
            'send_response' => 'ava/bqs/AvaliacaoUsuarioHistorico',
            'finish_assessment' => 'ava/bqs/AvaliacaoUsuario/{idAvaliacaoUsuario}/Finalizar/1',
            'get_all_the_notes_from_the_activities' => 'ava/bqs/AvaliacaoUsuario/0',
            'confirm_user_photo' => 'ava/bqs/AvaliacaoUsuarioReconhecimento'
        ],

        'http' => [
            'timeout' => 10,
            'retry' => 3
        ],

        // TODO AQUI TENHO QUE COLOCAR OS ENDPOINTS
    ];

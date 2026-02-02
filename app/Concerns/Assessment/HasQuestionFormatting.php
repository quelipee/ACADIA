<?php

namespace App\Concerns\Assessment;

use App\Domain\DTOs\Assessment\QuestionDTO;

trait HasQuestionFormatting {

    public function hasQuestionFormatting(array $question) {
        return QuestionDTO::fromApi($question);
    }

    protected function hasQuestionAllList(string $id) : array {
        $endpoint = str_replace(
            '{id}', $id,
            config('faculdade.endpoints.list_questions')
        );
        
        $response = $this->http->client()->get($endpoint);
        return $response['avaliacaoUsuarioHistoricos'];
    }

    protected function hasQuestionAllListProof(string $idAvaliacaoUsuario, array $token) : array {
        $endpoint = str_replace(
            ['{idAvaliacaoUsuario}', '{token}'],
            [$idAvaliacaoUsuario, $token['token']],
            config('faculdade.endpoints.list_questionsProof')
        );
        
        $response = $this->http->client()->get($endpoint);
        return $response['avaliacaoUsuarioHistoricos'];
    }

}
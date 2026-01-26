<?php

namespace App\Concerns\Assessment;

use App\Domain\DTOs\QuestionDTO;

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

}
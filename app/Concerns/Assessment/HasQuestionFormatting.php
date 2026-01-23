<?php

namespace App\Concerns\Assessment;

use App\Domain\DTOs\QuestionDTO;

trait HasQuestionFormatting {

    protected function hasQuestionFormatting(string $id, string $status) {
        $endpoint = str_replace(
            '{id}', $id,
            config('faculdade.endpoints.list_questions')
        );
        
        $response = $this->http->client()->get($endpoint);
        dd($response['avaliacaoUsuarioHistoricos']);
        return QuestionDTO::fromApi($response['avaliacaoUsuarioHistoricos'][0]);
    }

}
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
        dd($response['avaliacaoUsuarioHistoricos'][0]);

        // NAO ESQUECER DISSO TBM
        dd(QuestionDTO::validatedSubject($response['avaliacaoUsuarioHistoricos'][0]));
    }

}
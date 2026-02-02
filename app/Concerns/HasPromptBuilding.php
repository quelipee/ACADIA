<?php

namespace App\Concerns;

use App\Domain\DTOs\Assessment\QuestionDTO;

trait HasPromptBuilding {
    protected function buildPrompt(QuestionDTO $question) : string {
        $questao = 'just a response with ID' . PHP_EOL .$question->questao . PHP_EOL . PHP_EOL;
        foreach ($question->alternativas as $alternativa) {
            $questao .= $alternativa->id . ' | Texto: ' . $alternativa->valor . PHP_EOL . PHP_EOL; 
        }

        return $questao;
    }
}
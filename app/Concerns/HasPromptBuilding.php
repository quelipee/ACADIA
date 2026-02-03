<?php

namespace App\Concerns;

use App\Domain\DTOs\Assessment\QuestionDTO;

trait HasPromptBuilding {
    protected function buildPrompt(QuestionDTO $question) : string {
        $questao = 'Return only the ID of the correct answer.' . PHP_EOL . $question->questao . PHP_EOL . PHP_EOL;
        foreach ($question->alternativas as $alternativa) {
            $questao .= $alternativa->id . ' | Texto: ' . $alternativa->valor . PHP_EOL . PHP_EOL; 
        }
        
        $questao .= PHP_EOL . "Answer only with the ID, nothing else.";
        return $questao;
    }
}
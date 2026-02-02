<?php

namespace App\Domain\Contracts;

use App\Domain\DTOs\AI\AIAnswerDTO;
use App\Domain\DTOs\Assessment\QuestionDTO;

interface AIClientInterface {
    public function answerQuestion(QuestionDTO $question) : AIAnswerDTO;
}
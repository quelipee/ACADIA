<?php

namespace App\Domain\Contracts;

use App\Domain\DTOs\AIAnswerDTO;
use App\Domain\DTOs\QuestionDTO;

interface AIClientInterface {
    public function answerQuestion(QuestionDTO $question) : AIAnswerDTO;
}
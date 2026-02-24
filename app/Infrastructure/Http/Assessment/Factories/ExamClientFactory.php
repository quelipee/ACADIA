<?php

namespace App\Infrastructure\Http\Assessment\Factories;

use App\Domain\Contracts\Assessment\ExamClientInterface;
use App\Domain\Enums\ExamActivityType;
use App\Infrastructure\Http\Assessment\ApolClient;
use App\Infrastructure\Http\Assessment\ExamApolClient;
use App\Infrastructure\Http\Assessment\ExamClient;
use App\Infrastructure\Http\Assessment\MistaClient;
use InvalidArgumentException;

class ExamClientFactory {
    public function __construct(
        private ApolClient $apol_client,
        private ExamClient $exam_client,
        private MistaClient $mista_client,
        private ExamApolClient $exam_apol_client
    )
    {}
//ADJUST DEPOIS
    public function make(ExamActivityType $type) : ExamClientInterface {
        return match ($type) {
            ExamActivityType::APOL => $this->apol_client,
            // ExamActivityType::EXAM => $this->exam_client,
            ExamActivityType::MISTA => $this->mista_client,
            ExamActivityType::EXAM => $this->exam_apol_client,
            default => throw new InvalidArgumentException('ERROR NOT FOUND!!')
        };
    }
}

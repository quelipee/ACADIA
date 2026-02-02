<?php

namespace App\Infrastructure\Http\Assessment;

use App\Domain\Contracts\Assessment\ExamClientInterface;
use App\Domain\Enums\ExamActivityType;
use InvalidArgumentException;

class ExamClientFactory {
    public function __construct(
        private ApolClient $apol_client,
        private ExamClient $exam_client,
        private MistaClient $mista_client
    )
    {}

    public function make(ExamActivityType $type) : ExamClientInterface {
        return match ($type) {
            ExamActivityType::APOL => $this->apol_client,
            ExamActivityType::EXAM => $this->exam_client,
            ExamActivityType::MISTA => $this->mista_client,
            default => throw new InvalidArgumentException('ERROR NOT FOUND!!')
        };
    }
}
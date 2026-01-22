<?php

namespace App\Domain\Contracts\Assessment;

use App\Domain\Enums\ExamActivityType;

interface ExamClientInterface {
    public function listStudentActivity(int $idSalaVirtual, int $idSalaVirtualOfertaAproveitamento, ExamActivityType $value) : array;
    public function fetchFormattedQuestion(string $id, string $status);
}
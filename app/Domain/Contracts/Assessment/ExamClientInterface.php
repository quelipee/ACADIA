<?php

namespace App\Domain\Contracts\Assessment;

use App\Domain\Enums\ExamActivityType;

interface ExamClientInterface {
    public function listStudentActivity(int $idSalaVirtual, int $idSalaVirtualOfertaAproveitamento, string $value) : array;
}
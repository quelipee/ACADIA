<?php

namespace App\Infrastructure\Http\Assessment;

use App\Concerns\Assessment\HasAssessmentType;
use App\Domain\Contracts\Assessment\ExamClientInterface;
use App\Domain\Contracts\FaculdadeClientInterface;
use App\Domain\Enums\ExamActivityType;

class ApolClient implements ExamClientInterface{
    use HasAssessmentType;

    public function listStudentActivity(int $idSalaVirtual, int $idSalaVirtualOferta, string $value) : array {

        $eita = $this->HasAssessmentType($idSalaVirtual, $idSalaVirtualOferta, $value);
        dd($eita);
    }
}
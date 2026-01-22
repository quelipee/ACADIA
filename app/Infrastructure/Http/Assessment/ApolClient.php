<?php

namespace App\Infrastructure\Http\Assessment;

use App\Concerns\Assessment\HasAssessmentType;
use App\Domain\Contracts\Assessment\ExamClientInterface;
use App\Domain\Enums\ExamActivityType;
use InvalidArgumentException;

class ApolClient implements ExamClientInterface{
    use HasAssessmentType;
    
    public function name() : string {
        return ExamActivityType::APOL->value;
    }

    public function listStudentActivity(int $idSalaVirtual, int $idSalaVirtualOferta, ExamActivityType $value) : array {
        if(!isset($value) || $value->value !== $this->name()) {
            throw new InvalidArgumentException('Tipo invalido!!');
        }
        return $this->hasAssessmentType($idSalaVirtual, $idSalaVirtualOferta, $value->value);
    }

    public function fetchFormattedQuestion(string $id, string $status) {
        // TODO
    }
}
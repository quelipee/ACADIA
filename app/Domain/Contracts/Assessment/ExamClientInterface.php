<?php

namespace App\Domain\Contracts\Assessment;

use App\Domain\Enums\ExamActivityType;

interface ExamClientInterface {
    public function listStudentActivity(int $idSalaVirtual, int $idSalaVirtualOfertaAproveitamento, ExamActivityType $value) : array;
    public function listAllQuestion(string $id);
    public function confirmStartAssessment(string $cIdAvaliacao, int $try);
    public function getAllTheNotesFromTheActivities(string $cIdAvaliacao) :  array;
}
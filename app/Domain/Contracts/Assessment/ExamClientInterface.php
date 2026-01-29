<?php

namespace App\Domain\Contracts\Assessment;

interface ExamClientInterface {
    public function listStudentActivity(int $idSalaVirtual, int $idSalaVirtualOfertaAproveitamento) : array;
    public function listAllQuestion(string $id, ?string $token);
    public function confirmStartAssessment(string $cIdAvaliacao, int $try);
    public function getAllTheNotesFromTheActivities(string $cIdAvaliacao) :  array;
}
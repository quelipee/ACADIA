<?php

namespace App\Domain\Contracts\Assessment;

use App\Domain\DTOs\Assessment\ApolAttemptDTO;
use App\Domain\Enums\ExamActivityType;

interface ExamClientInterface {
    public function activityType() : ExamActivityType;
    public function listStudentActivity(int $idSalaVirtual, int $idSalaVirtualOfertaAproveitamento) : array;
    public function listAllQuestion(string $id, ?array $token) : array;
    public function confirmStartAssessment(string $cIdAvaliacao, string $cIdAvaliacaoVinculada, int $try) : ApolAttemptDTO;
    public function getAllTheNotesFromTheActivities(string $cIdAvaliacao) :  array;
}
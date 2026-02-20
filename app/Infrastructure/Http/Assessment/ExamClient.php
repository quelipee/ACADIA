<?php

namespace App\Infrastructure\Http\Assessment;

use App\Concerns\Assessment\HasAssessmentType;
use App\Concerns\Assessment\HasQuestionFormatting;
use App\Domain\Contracts\Assessment\ExamClientInterface;
use App\Domain\DTOs\Assessment\ActivityDTO;
use App\Domain\Enums\ExamActivityType;

class ExamClient implements ExamClientInterface{
    use HasAssessmentType, HasQuestionFormatting;

    public function activityType() : ExamActivityType {
        return ExamActivityType::EXAM;
    }

    public function listStudentActivity(int $idSalaVirtual, int $idSalaVirtualOferta) : array {
        $response = $this->http->client()->get(config('faculdade.endpoints.list_proof'));
        $list_activities = [];

        foreach ($response['avaliacaoUsuarios'] as $data) {
                $list_activities[] = ActivityDTO::fromApi($data);
        }

        $list_activities = collect($list_activities)
        ->where('nomeClassificacaoTipo', $this->activityType()->value)
        ->values()
        ->toArray();

        return $list_activities;
    }

    public function listAllQuestion(string $id, ?array $token) : array {
        return $this->hasQuestionAllListProof($id, $token);
    }
}

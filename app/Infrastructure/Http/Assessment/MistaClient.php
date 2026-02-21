<?php

namespace App\Infrastructure\Http\Assessment;

use App\Concerns\Assessment\HasActivityList;
use App\Concerns\Assessment\HasAssessmentType;
use App\Concerns\Assessment\HasQuestionFormatting;
use App\Domain\Contracts\Assessment\ExamClientInterface;
use App\Domain\Enums\ExamActivityType;

class MistaClient implements ExamClientInterface {
    use HasAssessmentType, HasQuestionFormatting, HasActivityList;

    public function activityType() : ExamActivityType {
        return ExamActivityType::MISTA;
    }

    public function listAllQuestion(string $id, ?array $token) : array {
        return $this->hasQuestionAllList($id);
    }
}

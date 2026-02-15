<?php

namespace App\Application\Services;

use App\Domain\Enums\ActivityStatus;
use App\Domain\Enums\ExamActivityType;
use App\Infrastructure\Http\Assessment\Factories\ExamClientFactory;

class ResolveApolAttemptsService {
    public function __construct(
        protected ExamClientFactory $factory
    )
    {}

    public function resolver(string $cIdAvaliacao, string|null $cIdAvaliacaoVinculada, ExamActivityType $type) {
        $client = $this->factory->make($type);
        $list_try = $client->getAllTheNotesFromTheActivities($cIdAvaliacao);
        $maxTry = 0;

        foreach($list_try as $list) {
            if ($list->status == ActivityStatus::START->value 
            and $list->tentativa <= $list->tentativaTotal) {
                return $list;
            }

            $maxTry = max($maxTry,$list->tentativa);
        }

        return $client->confirmStartAssessment($cIdAvaliacao, $cIdAvaliacaoVinculada, $maxTry +1);
    }
}
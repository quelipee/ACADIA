<?php

namespace App\Application\Services;

use App\Domain\Contracts\Assessment\ExamClientInterface;
use App\Domain\Enums\ActivityStatus;
use App\Domain\Enums\ExamActivityType;
use App\Infrastructure\Http\Assessment\ExamClientFactory;

class ResolveApolAttemptsService {
    public function __construct(
        protected ExamClientFactory $factory
    )
    {}

    public function resolver(string $cIdAvaliacao, ExamActivityType $type) {
        $client = $this->factory->make($type);
        $list_try = $client->getAllTheNotesFromTheActivities($cIdAvaliacao);
       
        foreach($list_try as $key => $list) {
            if ($list->status == ActivityStatus::START->value 
            and $list->tentativa <= $list->tentativaTotal) {
                return $list;
            }

            if($key+1 === count($list_try)){
                return $client->confirmStartAssessment($cIdAvaliacao, $list->tentativa +1);
            }
        }
    }
}
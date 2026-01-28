<?php

namespace App\Application\Services;

use App\Domain\Contracts\Assessment\ExamClientInterface;
use App\Domain\Enums\ActivityStatus;

class ResolveApolAttemptsService {
    public function __construct(
        protected ExamClientInterface $client
    )
    {}

    public function resolver(string $cIdAvaliacao) {
        $list_try = $this->client->getAllTheNotesFromTheActivities($cIdAvaliacao);
       
        foreach($list_try as $key => $list) {
            if ($list->status == ActivityStatus::START->value 
            and $list->tentativa <= $list->tentativaTotal) {
                return $list;
            }

            if($key+1 === count($list_try)){
                return $this->client->confirmStartAssessment($cIdAvaliacao, $list->tentativa +1);
            }
        }
    }
}
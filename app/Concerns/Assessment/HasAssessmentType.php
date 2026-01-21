<?php

namespace App\Concerns\Assessment;

use App\Domain\Contracts\FaculdadeClientInterface;
use App\Domain\DTOs\ActivitiesDTO;
use App\Domain\Enums\ExamActivityType;

trait HasAssessmentType {

    public function __construct(
        private readonly FaculdadeClientInterface $http,
    )
    {}

    protected function HasAssessmentType(int $idSalaVirtual, int $idSalaVirtualOferta, string $type) {
        $response = $this->http->client()->get(config('faculdade.endpoints.list_activities'),[
            'idSalaVirtual' => $idSalaVirtual,
            'idSalaVirtualOfertaAtual' => $idSalaVirtualOferta,
        ]);
        
        foreach ($response['avaliacaoUsuarios'] as $data) {
            $list_activities[] = ActivitiesDTO::validatedActivities($data);
        }
        
        $list_activities = collect($list_activities)
        ->where('nomeClassificacaoTipo', $type)
        ->values()
        ->toArray();

        return $list_activities;
    }
}
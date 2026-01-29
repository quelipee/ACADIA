<?php

namespace App\Concerns\Assessment;

use App\Domain\Contracts\FaculdadeClientInterface;
use App\Domain\DTOs\ActivitiesDTO;

trait HasAssessmentType {

    public function __construct(
        private readonly FaculdadeClientInterface $http,
    )
    {}

    protected function hasAssessmentType(int $idSalaVirtual, int $idSalaVirtualOferta, string $type) : array {
        $response = $this->http->client()->get(config('faculdade.endpoints.list_activities'),[
            'idSalaVirtual' => $idSalaVirtual,
            'idSalaVirtualOferta' => $idSalaVirtualOferta,
            // 'idSalaVirtualOfertaAtual' => $idSalaVirtualOferta, // isso é a de ingles
        ]);

        foreach ($response['avaliacaoUsuarios'] as $data) {
            $list_activities[] = ActivitiesDTO::fromApi($data);
        }
        
        $list_activities = collect($list_activities)
        ->where('nomeClassificacaoTipo', $type)
        ->values()
        ->toArray();
        
        return $list_activities;
    }

    public function photoConfirmation (int $id) {
        $response = $this->http->client()->get(config('faculdade.endpoints.confirm_user_photo'),[
            'id' => $id,
            'rkg' => fake()->imageUrl(),
        ]);
        dd($response->json());
        return $response['avaliacaoUsuarioToken']; // depois crio um dto
    }
}
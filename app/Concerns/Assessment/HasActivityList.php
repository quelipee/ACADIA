<?php
namespace App\Concerns\Assessment;

use App\Domain\DTOs\ActivitiesDTO;

trait HasActivityList {

    public function listStudentActivity(int $idSalaVirtual, int $idSalaVirtualOferta) : array {
        $response = $this->http->client()->get(config('faculdade.endpoints.list_activities'),[
            'idSalaVirtual' => $idSalaVirtual,
            $this->activityType()->ofertaKey() => $idSalaVirtualOferta
        ]);

        foreach ($response['avaliacaoUsuarios'] as $data) {
            $list_activities[] = ActivitiesDTO::fromApi($data);
        }
        
        $list_activities = collect($list_activities)
        ->where('nomeClassificacaoTipo', $this->activityType()->value)
        ->values()
        ->toArray();

        return $list_activities;
    }
}
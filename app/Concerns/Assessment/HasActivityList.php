<?php
namespace App\Concerns\Assessment;

use App\Domain\DTOs\Assessment\ActivityDTO;

trait HasActivityList {

    public function listStudentActivity(int $idSalaVirtual, int $idSalaVirtualOferta) : array {
        $list_activities = [];

        $response = $this->http->client()->get(config('faculdade.endpoints.list_activities'),[
            'idSalaVirtual' => $idSalaVirtual,
            $this->activityType()->ofertaKey() => $idSalaVirtualOferta
        ]);

        foreach ($response['avaliacaoUsuarios'] ?? [] as $item) {
            $list_activities[] = ActivityDTO::fromApi($item);
        }

        $list_activities = collect($list_activities)
        ->where('nomeClassificacaoTipo', $this->activityType()->value)
        ->values()
        ->toArray();

        return $list_activities;
    }
}


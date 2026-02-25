<?php

namespace App\Concerns\Assessment;

use App\Domain\Contracts\FaculdadeClientInterface;
use App\Domain\DTOs\Assessment\ApolAttemptDTO;
use InvalidArgumentException;

trait HasAssessmentType {
    /**
     * @throws InvalidArgumentException
     */
    public function confirmStartAssessment(string $cIdAvaliacao, string|null $cIdAvaliacaoVinculada, int $try) : ApolAttemptDTO {
        $endpoint = str_replace(
            '{try}', $try,
            config('faculdade.endpoints.confirm_start_assessment')
        );

        $response = $this->http->client()->get($endpoint,[
            'ap' => 'false',
            'cIdAvaliacao' => $cIdAvaliacao,
            'idAvaliacaoVinculada' => $cIdAvaliacaoVinculada,
            'cache' => random_int(1000000000000, 9999999999999)
        ]);

        return isset($response['avaliacaoUsuario']) ?
        ApolAttemptDTO::fromApi($response['avaliacaoUsuario']) :
        throw new InvalidArgumentException($response['mensagens'][0]);
    }

    public function getAllTheNotesFromTheActivities(string $cIdAvaliacao) : array {
        $response = $this->http->client()->get(config('faculdade.endpoints.get_all_the_notes_from_the_activities'),[
            'cIdAvaliacao' => $cIdAvaliacao,
        ]);

        return collect($response['avaliacaoUsuarios'])
        ->map(fn($data) => ApolAttemptDTO::fromApi($data))
        ->all();
    }

    public function photoConfirmation (int $id) {
        $response = $this->http->client()->post(config('faculdade.endpoints.confirm_user_photo'),[
            'id' => $id,
            'rkg' => fake()->imageUrl(),
        ]);

        if ($response->status() != 201 and $response->status() != 200) {
            return null;
        }
        return $response['avaliacaoUsuarioToken']; // depois crio um dto
    }
}

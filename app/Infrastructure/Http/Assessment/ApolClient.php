<?php

namespace App\Infrastructure\Http\Assessment;

use App\Concerns\Assessment\HasAssessmentType;
use App\Concerns\Assessment\HasQuestionFormatting;
use App\Domain\Contracts\Assessment\ExamClientInterface;
use App\Domain\DTOs\ApolAttemptDTO;
use App\Domain\Enums\ExamActivityType;
use InvalidArgumentException;

class ApolClient implements ExamClientInterface{
    use HasAssessmentType,HasQuestionFormatting;
    
    public function name() : string {
        return ExamActivityType::MISTA->value;
    }

    public function listStudentActivity(int $idSalaVirtual, int $idSalaVirtualOferta) : array {
        return $this->hasAssessmentType($idSalaVirtual, $idSalaVirtualOferta, $this->name());
    }

    public function confirmStartAssessment(string $cIdAvaliacao, int $try) {
        $endpoint = str_replace(
            '{try}', $try,
            config('faculdade.endpoints.confirm_start_assessment')
        ); 
        
        $response = $this->http->client()->get($endpoint,[
            'ap' => 'false',
            'cIdAvaliacao' => $cIdAvaliacao,
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
        ->map(fn ($data) => ApolAttemptDTO::fromApi($data))
        ->all();
    }

    public function listAllQuestion(string $id, ?string $token) {
        return $this->hasQuestionAllList($id);
    }
}
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
        return ExamActivityType::APOL->value;
    }

    public function listStudentActivity(int $idSalaVirtual, int $idSalaVirtualOferta, ExamActivityType $value) : array {
        if(!isset($value) || $value->value !== $this->name()) {
            throw new InvalidArgumentException('Tipo invalido!!');
        }
        return $this->hasAssessmentType($idSalaVirtual, $idSalaVirtualOferta, $value->value);
    }

    public function confirmStartAssessment(string $cIdAvaliacao, int $try) : ApolAttemptDTO {
        $endpoint = str_replace(
            '{try}', $try,
            config('faculdade.endpoints.confirm_start_assessment')
        ); 
        
        $response = $this->http->client()->get($endpoint,[
            'ap' => 'false',
            'cIdAvaliacao' => $cIdAvaliacao,
            'cache' => random_int(1000000000000, 9999999999999)
        ]);
        
        return ApolAttemptDTO::fromApi($response['avaliacaoUsuario']); //TENHO QUE VOLTAR AQUI DEPOIS, PQ AQUI PEGA O ID QUE NAO PEGA QUANDO A ATIVIDADE NUNCA FOI FEITA
    }

    public function getAllTheNotesFromTheActivities(string $cIdAvaliacao) : array {
        $response = $this->http->client()->get(config('faculdade.endpoints.get_all_the_notes_from_the_activities'),[
            'cIdAvaliacao' => $cIdAvaliacao,
        ]);

        return collect($response['avaliacaoUsuarios'])
        ->map(fn ($data) => ApolAttemptDTO::fromApi($data))
        ->all();
    }

    public function listAllQuestion(string $id) {
        return $this->hasQuestionAllList($id);
    }
}
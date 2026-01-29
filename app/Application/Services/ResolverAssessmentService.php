<?php

namespace App\Application\Services;

use App\Domain\Contracts\AIClientInterface;
use App\Domain\Contracts\FaculdadeClientInterface;
use App\Domain\Enums\ExamActivityType;
use App\Infrastructure\Http\Assessment\ExamClientFactory;
use InvalidArgumentException;

class ResolverAssessmentService {

    public function __construct(
        private AIClientInterface $client,
        private readonly FaculdadeClientInterface $http,
        private ExamClientFactory $factory,
        private ResolveApolAttemptsService $resolve_apol
    )
    {}
    protected $avaliacaoUsuarioToken = null;

    public function resolver(array $disciplina ,ExamActivityType $type) : void {
        $clientService = $this->factory->make($type);
        
        $activities = $clientService->listStudentActivity($disciplina[30]->id,$disciplina[30]->idSalaVirtualOfertaAproveitamento);

        if ($type == ExamActivityType::EXAM) {
            //ajustar depois
            $this->avaliacaoUsuarioToken = $clientService->photoConfirmation($activities[2]->id);
        }
        
        $data = $this->resolve_apol->resolver($activities[0]->cID, $type);
        // TODO terminar aqui, pq em exame eu preciso passar o token, mas eu estou usando a mesma interface para os dois (exercicios e exame) e o exame precisa de token
        $list_question = $clientService->listAllQuestion($data->id, $this->avaliacaoUsuarioToken);

        foreach ($list_question as $list) {
            $question = $clientService->hasQuestionFormatting($list);
            $aiAnswer = $this->client->answerQuestion($question);

            foreach($question->alternativas as $alternativa) {

                if((int) $aiAnswer->alternativeId == $alternativa->id) {
                    
                    $response = $this->http->client()
                    ->put(config('faculdade.endpoints.send_response'),[
                        'id' => $question->id,
                        'idQuestaoAlternativa' => $alternativa->idQuestaoAlternativa,
                        'idAvaliacaoUsuario' => $question->idAvaliacaoUsuario,
                    ]);

                    if(!$response->successful()){
                        dd($response->status());
                        throw new InvalidArgumentException('erro api!!');
                    }

                    print_r($alternativa);
                }
            }
            sleep(10);
        }

        $endpoint = str_replace(
            '{idAvaliacaoUsuario}', $question->idAvaliacaoUsuario,
            config('faculdade.endpoints.finish_assessment')
        );
        $this->http->client()->get($endpoint);
    }
}
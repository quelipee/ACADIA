<?php

namespace App\Application\Services;

use App\Domain\Contracts\AIClientInterface;
use App\Domain\Contracts\FaculdadeClientInterface;
use App\Domain\Enums\AiProvider;
use App\Domain\Enums\ExamActivityType;
use App\Infrastructure\Http\Assessment\Factories\AiProviderFactory;
use App\Infrastructure\Http\Assessment\Factories\ExamClientFactory;
use InvalidArgumentException;

class ResolverAssessmentService {

    public function __construct(
        private AiProviderFactory $factoryProvider,
        private readonly FaculdadeClientInterface $http,
        private ExamClientFactory $factory,
        private ResolveApolAttemptsService $resolve_apol
    )
    {}
    protected $avaliacaoUsuarioToken = null;

    public function resolver(array $disciplina ,ExamActivityType $type, AiProvider $provider) : void {
        // $clientService = $this->factory->make($type);

        // // ingles usa o idSalaVirtualOfertaAtual e a graduacao usa idSalaVirtualOfertaAproveitamento
        // $all_activities = $clientService->listStudentActivity($disciplina[2]->id,$disciplina[2]->idSalaVirtualOfertaAproveitamento);
        // $activity = $this->resolve_apol->resolver($all_activities[0]->cID, $all_activities[0]->cIdAvaliacaoVinculada, $type);
        dd('$activity');
        if ($type == ExamActivityType::EXAM) {
            $this->avaliacaoUsuarioToken = $clientService->photoConfirmation($activity->id);
        }

        $list_question = $clientService->listAllQuestion($activity->id, $this->avaliacaoUsuarioToken);

        $providerService = $this->factoryProvider->make($provider);
        print_r($providerService->type());

        foreach ($list_question as $list) {
            $question = $clientService->hasQuestionFormatting($list);
            $aiAnswer = $providerService->answerQuestion($question);

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
                        throw new InvalidArgumentException($response->status());
                    }

                    print_r($alternativa);
                }
            }
            sleep(5);
        }

        $endpoint = str_replace(
            '{idAvaliacaoUsuario}', $question->idAvaliacaoUsuario,
            config('faculdade.endpoints.finish_assessment')
        );
        $this->http->client()->get($endpoint);
    }

    public function get_all_activities(int $id, int $idSalaVirtualOfertaAproveitamento, ExamActivityType $type) {
        $clientService = $this->factory->make($type);
        
        $all_activities = $clientService->listStudentActivity($id,$idSalaVirtualOfertaAproveitamento);
        return $all_activities;
    }
}

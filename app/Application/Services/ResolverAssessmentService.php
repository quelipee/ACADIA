<?php

namespace App\Application\Services;

use App\Concerns\Assessment\HasAssessmentType;
use App\Domain\Contracts\FaculdadeClientInterface;
use App\Domain\DTOs\Assessment\AnswerKeyDTO;
use App\Domain\DTOs\Assessment\QuestionDTO;
use App\Domain\Enums\AiProvider;
use App\Domain\Enums\ExamActivityType;
use App\Infrastructure\Http\Assessment\Factories\AiProviderFactory;
use App\Infrastructure\Http\Assessment\Factories\ExamClientFactory;
use InvalidArgumentException;

class ResolverAssessmentService {
    use HasAssessmentType;

    public function __construct(
        private AiProviderFactory $factoryProvider,
        private readonly FaculdadeClientInterface $http,
        private ExamClientFactory $factory,
        private ResolveApolAttemptsService $resolve_apol
    )
    {}
    protected $avaliacaoUsuarioToken = null;

    public function formattedQuestions($disciplina) : array {
        return $this->get_list_question($disciplina);
    }

    public function resolver(array $data ,int $idQuestaoAlternativa) : void {
        $response = $this->http->client()
        ->put(config('faculdade.endpoints.send_response'),[
            'id' => $data['question']['id'],
            'idQuestaoAlternativa' => $idQuestaoAlternativa,
            'idAvaliacaoUsuario' => $data['question']['idAvaliacaoUsuario'],
        ]);

        if(!$response->successful()){
            dd($response->status());
            throw new InvalidArgumentException($response->status());
        }

        print_r($idQuestaoAlternativa);
    }

    public function submit_activity(array $data) : void {
        $endpoint = str_replace(
            '{idAvaliacaoUsuario}', $data['question']['idAvaliacaoUsuario'],
            config('faculdade.endpoints.finish_assessment')
        );
        $this->http->client()->get($endpoint);
    }

    public function resolverAI(array $disciplina , AiProvider $provider) : void {
        $type = ExamActivityType::from($disciplina['data']['nomeClassificacaoTipo']);
        $clientService = $this->factory->make($type);

        $activity = $this->resolve_apol->resolver($disciplina['data']['cID'], $disciplina['data']['cIdAvaliacaoVinculada'], $type);

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

    public function attempts(string $cId) {
        return $this->getAllTheNotesFromTheActivities($cId);
    }

    public function get_list_question(array $disciplina) : array {
        $type = ExamActivityType::from($disciplina['data']['nomeClassificacaoTipo']);
        $clientService = $this->factory->make($type);

        $activity = $this->resolve_apol->resolver($disciplina['data']['cID'], $disciplina['data']['cIdAvaliacaoVinculada'], $type);

        if ($type == ExamActivityType::EXAM) {
            $this->avaliacaoUsuarioToken = $clientService->photoConfirmation($activity->id);
        }

        $list =  $clientService->listAllQuestion($activity->id, $this->avaliacaoUsuarioToken);
        foreach($list as $data) {
            $questionsFormmated[] = $clientService->hasQuestionFormatting($data);
        }

        return $questionsFormmated;
    }

    public function get_answer_key_list(array $disciplinaAttempt)  {
        $type = ExamActivityType::from($disciplinaAttempt['data']['nomeClassificacaoTipo']);
        $clientService = $this->factory->make($type);

        $list =  $clientService->listAllQuestion($disciplinaAttempt['data']['id'], null);
        foreach ($list as $data) {
            $questionsFormmated[] = AnswerKeyDTO::fromApi($data);
        }

        return  $questionsFormmated;
    }
}

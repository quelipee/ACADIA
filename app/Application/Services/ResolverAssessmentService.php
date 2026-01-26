<?php

namespace App\Application\Services;

use App\Domain\Contracts\AIClientInterface;
use App\Domain\Contracts\Assessment\ExamClientInterface;
use App\Domain\Contracts\FaculdadeClientInterface;
use InvalidArgumentException;

class ResolverAssessmentService {

    public function __construct(
        private AIClientInterface $client,
        private readonly FaculdadeClientInterface $http,
        private ExamClientInterface $clientService,
    )
    {}

    public function resolver(string $id, string $cIdAvaliacao, int $try) {      
        $this->clientService->confirmStartAssessment($cIdAvaliacao, $try);
        $list_question = $this->clientService->listAllQuestion($id);

        foreach ($list_question as $list) {
            $question = $this->clientService->hasQuestionFormatting($list);
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

        // $this->http->client()->get("https://univirtus.uninter.com/ava/bqs/AvaliacaoUsuario/{$question->idAvaliacaoUsuario}/Finalizar/1");
    }
}
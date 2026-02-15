<?php

namespace App\Infrastructure\Http;

use App\Concerns\HasPromptBuilding;
use App\Domain\Contracts\AIClientInterface;
use App\Domain\DTOs\AI\AIAnswerDTO;
use App\Domain\DTOs\Assessment\QuestionDTO;
use App\Domain\Enums\AiProvider;
use OpenAI;

class OpenAIClient implements AIClientInterface {

    private $client;
    use HasPromptBuilding;

    public function __construct(
        private readonly string $accessToken,
    )
    {
        $this->client = OpenAI::client($accessToken);
    }

    public function type() : string {
        return AiProvider::OPENAI->value . PHP_EOL;
    }

    public function answerQuestion(QuestionDTO $question) : AIAnswerDTO {
        $prompt = $this->buildPrompt($question); 
        $response = $this->client->responses()->create([
            'model' => 'gpt-4o',
            'input' => $prompt,
        ]);

        return AIAnswerDTO::fromResponse($response->outputText);
    }
}

<?php

namespace App\Infrastructure\Http;

use App\Concerns\HasPromptBuilding;
use App\Domain\Contracts\AIClientInterface;
use App\Domain\DTOs\AI\AIAnswerDTO;
use App\Domain\DTOs\Assessment\QuestionDTO;
use App\Domain\Enums\AiProvider;
use GeminiAPI\Client;
use GeminiAPI\Resources\ModelName;
use GeminiAPI\Resources\Parts\TextPart;

class GeminiClient implements AIClientInterface{

    private Client $client;
    use HasPromptBuilding;

    public function __construct(
        private readonly string $accessToken,
    )
    {
        $this->client = new Client($accessToken);
    }

    public function type() : string {
        return AiProvider::GEMINI->value . PHP_EOL;
    }

    public function answerQuestion(QuestionDTO $question) : AIAnswerDTO {
        $prompt = $this->buildPrompt($question);
        $response = $this->client->generativeModel(ModelName::GEMINI_2_5_FLASH)
        ->generateContent(new TextPart($prompt));

        return AIAnswerDTO::fromResponse($response->text());
    }
}

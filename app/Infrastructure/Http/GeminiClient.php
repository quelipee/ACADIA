<?php

namespace App\Infrastructure\Http;

use App\Concerns\HasPromptBuilding;
use App\Domain\Contracts\AIClientInterface;
use App\Domain\DTOs\AIAnswerDTO;
use App\Domain\DTOs\QuestionDTO;
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
    
    public function answerQuestion(QuestionDTO $question) : AIAnswerDTO {
        $prompt = $this->buildPrompt($question); 
        $response = $this->client->generativeModel(ModelName::GEMINI_2_5)
        ->generateContent(new TextPart($prompt));

        return AIAnswerDTO::fromGeminiResponse($response->text());
    }
}
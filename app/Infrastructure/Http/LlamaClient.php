<?php

namespace App\Infrastructure\Http;

use App\Concerns\HasPromptBuilding;
use App\Domain\Contracts\AIClientInterface;
use App\Domain\DTOs\Assessment\QuestionDTO;
use App\Domain\DTOs\AI\AIAnswerDTO;
use App\Domain\Enums\AiProvider;
use Illuminate\Support\Facades\Http;


class LlamaClient implements AIClientInterface{
    use HasPromptBuilding;

    public function __construct(
        private readonly string $baseUrl,
        private readonly string $accessToken
    )
    {}

    public function type() : string {
        return AiProvider::LLAMA->value . PHP_EOL;
    }

    public function answerQuestion(QuestionDTO $question): AIAnswerDTO
    {
        $prompt = $this->buildPrompt($question);
        $response = Http::withHeaders($this->headers())
        ->post($this->baseUrl,[
            'model' => 'meta-llama/llama-3.3-70b-instruct',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ]
        ]);
        return AIAnswerDTO::fromResponse($response['choices'][0]['message']['content']);
    }

    private function headers() : array{
        return [
            'Authorization' => 'Bearer ' . $this->accessToken,
            'Content-Type' => 'application/json'
        ];
    }
}
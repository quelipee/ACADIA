<?php

namespace App\Infrastructure\Http\Assessment\Factories;

use App\Domain\Contracts\AIClientInterface;
use App\Domain\Enums\AiProvider;
use App\Infrastructure\Http\GeminiClient;
use App\Infrastructure\Http\LlamaClient;
use InvalidArgumentException;
use OpenAI;

class AiProviderFactory {

    public function make(AiProvider $type) : AIClientInterface {
        return match ($type) {
            AiProvider::GEMINI => new GeminiClient(config('services.gemini.access_token')),
            AiProvider::OPENAI => new GeminiClient(config('services.openai.access_token')),
            AiProvider::LLAMA => new LlamaClient(
                config('faculdade.base_urls.openrouter_base_url'),
                config('services.llama.access_token')
                ),
            default => throw new InvalidArgumentException('ERROR NOT FOUND!!')
        };
    }
}

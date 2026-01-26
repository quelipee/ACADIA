<?php

namespace App\Domain\DTOs;

use InvalidArgumentException;

readonly class AIAnswerDTO {
    public function __construct(
        public int $alternativeId,
        public string $rawResponse
    )
    {}

    public static function fromGeminiResponse(string $text) : AIAnswerDTO {
        if(!isset($text)){
            throw new InvalidArgumentException('ERRO ID INVALID');
        }
        
        return new self(
            alternativeId: (int) trim($text),
            rawResponse: $text,
        );
    }
}
<?php

namespace App\Domain\Enums;

enum AiProvider : string {
    case GEMINI = 'gemini';
    case LLAMA = 'llama';
    case OPENAI = 'openai';
}
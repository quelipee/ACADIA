<?php

namespace App\Domain\Enums;

enum ExamActivityType : string {
    case APOL = 'Objetiva';
    case EXAM = 'Discursiva';
    case MISTA = 'Mista';

    public function ofertaKey(): string
    {
        return match ($this) {
            self::MISTA => 'idSalaVirtualOfertaAtual',
            default => 'idSalaVirtualOferta',
        };
    }
}
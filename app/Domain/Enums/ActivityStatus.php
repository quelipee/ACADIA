<?php

namespace App\Domain\Enums;

use InvalidArgumentException;

enum ActivityStatus : string {
    case START = 'Aguardando início';
    case FINISHED = 'Finalizada';

    public static function fromExternal(string $data) : self {
        return match(mb_strtolower($data)){
            'Aguardando início' => self::START,
            'Finalizada' => self::FINISHED,
            default => throw new InvalidArgumentException(
                `Status de graduação inválido: {$data}`
            )
        };
    }
}
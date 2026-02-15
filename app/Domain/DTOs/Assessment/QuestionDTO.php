<?php

namespace App\Domain\DTOs\Assessment;

use Illuminate\Support\Str;
use InvalidArgumentException;

readonly class QuestionDTO {
    public function __construct(
        public int $id,
        public int $idAvaliacaoUsuario,
        public int $idQuestao,
        public int $idQuestaoAlternativa,
        public string $questao,
        public array $alternativas,
    )
    {}

    public static function fromApi(array $request) : QuestionDTO {
        if (!isset($request['id'])) {
            throw new InvalidArgumentException('Essa materia não existe!!');
        }

        $questao = self::formattedText($request['questao']);

        return new self(
            id: $request['id'],
            idAvaliacaoUsuario: $request['idAvaliacaoUsuario'],
            idQuestao: $request['idQuestao'],
            idQuestaoAlternativa: $request['alternativas'][0]['questaoAlternativaAtributos'][0]['idQuestaoAlternativa'],
            questao: $questao,
            alternativas: self::formattedAlternative($request['alternativas']),
        );
    }

    public static function formattedText(string $request) : string {
        $strip_tags = html_entity_decode(
            strip_tags($request),
            ENT_QUOTES | ENT_HTML5,
            'UTF-8'
        );

        $text = Str::of($strip_tags)
            ->replace("\u{A0}", ' ')
            ->squish()
            ->trim();

        return $text;
    }

    public static function formattedAlternative(array $request) {
        return  collect($request)
        ->map(fn($data) => AlternativeDTO::fromApi($data))
        ->all();
    }
} 
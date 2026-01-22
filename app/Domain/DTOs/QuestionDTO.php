<?php

namespace App\Domain\DTOs;

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

    public static function validatedSubject(array $request) : QuestionDTO {
        if (!$request['id']) {
            throw new InvalidArgumentException('Essa materia não existe!!');
        }

        $questao = self::formattedText($request['questao']);
        $sua = self::formattedAlternative($request['alternativas']);

        dd($sua);

        return new self(
            id: $request['id'],
            idAvaliacaoUsuario: $request['idAvaliacaoUsuario'],
            idQuestao: $request['idQuestao'],
            idQuestaoAlternativa: $request['idQuestaoAlternativa'],
            questao: $questao,
            alternativas: $request['alternativas'],
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
        //TODO AQUI TEM QUE AJUSTAR AINDA
        return  collect($request)->map(function ($data) {
            // dd($data);
            return [
                'id' => $data['id'],
                'idQuestaoAlternativa' => $data['questaoAlternativaAtributos'][0]['idQuestaoAlternativa'],
                'valor' => self::formattedText($data['questaoAlternativaAtributos'][0]['valor']),
            ];
        })->all();
    }
} 
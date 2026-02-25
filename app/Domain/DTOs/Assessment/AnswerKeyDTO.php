<?php

namespace App\Domain\DTOs\Assessment;

use App\Support\TextFormatter;
use Carbon\Carbon;
use InvalidArgumentException;

readonly class AnswerKeyDTO {
    public function __construct(
        public int $id,
        public int $idAvaliacaoUsuario,
        public int $idQuestao,
        public int $idQuestaoAlternativa,
        public string $questao,
        public array $alternativas,
        public int $notaQuestao,
        public int|null $peso,
        public string|null $comando,
        public int $tentativa,
        public int $ordem,
        public int $percentualAcerto,
        public Carbon $dataCorrecao,

    )
    {}

    public static function fromApi(array $request) : AnswerKeyDTO {

        if (!isset($request['id'])) {
            throw new InvalidArgumentException('Essa materia não existe!!');
        }

        $questao = TextFormatter::format($request['questao']);

        return new self(
            id: $request['id'],
            idAvaliacaoUsuario: $request['idAvaliacaoUsuario'],
            idQuestao: $request['idQuestao'],
            idQuestaoAlternativa: $request['alternativas'][0]['questaoAlternativaAtributos'][0]['idQuestaoAlternativa'],
            questao: $questao,
            alternativas: $request['alternativas'],
            notaQuestao: $request['notaQuestao'],
            peso: $request['peso'],
            comando: $request['comando'],
            tentativa: $request['tentativa'],
            ordem: $request['ordem'],
            percentualAcerto: $request['percentualAcerto'],
            dataCorrecao: Carbon::parse($request['dataCorrecao']),
        );
    }
}

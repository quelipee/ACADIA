<?php

namespace App\Domain\DTOs\Assessment;

readonly class AlternativeDTO {
    public function __construct(
        public int $id,
        public int $idQuestaoAlternativa,
        public string $valor
    )
    {}

    public static function fromApi(array $data) : AlternativeDTO {
        return new self(
            id: $data['questaoAlternativaAtributos'][0]['id'],
            idQuestaoAlternativa: $data['questaoAlternativaAtributos'][0]['idQuestaoAlternativa'],
            valor: QuestionDTO::formattedText($data['questaoAlternativaAtributos'][0]['valor']),
        );
    }
}
<?php
namespace App\Domain\DTOs;

use InvalidArgumentException;

readonly class DisciplinaDTO {
    public function __construct(
        public int $id,
        public string $cId,
        public int $idCurso,
        public int $idSalaVirtualOfertaAtual,
        public int|null $idSalaVirtualOfertaAproveitamento,
        public string $nome,
    )
    {}

    public static function fromApi(array $request) : DisciplinaDTO {
        if (!$request['id']) {
            throw new InvalidArgumentException('Essa materia não existe!!');
        }

        return new self(
            id: $request['id'],
            cId: $request['cId'],
            idCurso: $request['idCurso'],
            idSalaVirtualOfertaAtual: $request['idSalaVirtualOfertaAtual'],
            idSalaVirtualOfertaAproveitamento: $request['idSalaVirtualOfertaAproveitamento'],
            nome: $request['nome'],
        );
    }
}
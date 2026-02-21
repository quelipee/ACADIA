<?php
namespace App\Domain\DTOs\Academic;

use InvalidArgumentException;

readonly class DisciplinaDTO {
    public function __construct(
        public int $id,
        public string $cId,
        public int $idCurso,
        public int $idSalaVirtualOfertaAtual,
        public int|null $idSalaVirtualOfertaAproveitamento,
        public string $nome,
        public bool $statusConcluido,
    )
    {}

    public static function fromApi(array $request) : DisciplinaDTO {
        if (!isset($request['id'])) {
            throw new InvalidArgumentException('Essa materia não existe!!');
        }

        $idSalaVirtualOfertaAproveitamento = $request['idSalaVirtualOfertaAproveitamento']
        ?? $request['idSalaVirtualOfertaAtual'];

        return new self(
            id: $request['id'],
            cId: $request['cId'],
            idCurso: $request['idCurso'],
            idSalaVirtualOfertaAtual: $request['idSalaVirtualOfertaAtual'],
            idSalaVirtualOfertaAproveitamento: $idSalaVirtualOfertaAproveitamento,
            nome: $request['nome'],
            statusConcluido: $request['statusConcluido']
        );
    }
}

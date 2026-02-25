<?php

namespace App\Domain\DTOs\Assessment;

readonly class ApolAttemptDTO {
    public function __construct(
        public int $id,
        public int $idAvaliacao,
        public int $idUsuario,
        public int $tentativa,
        public int $tentativaTotal,
        public int|null $nota,
        public string|null $status,
        public string $nomeClassificacaoTipo,
        public string $cID,
        public string|null $cIdAvaliacaoVinculada
    )
    {}

    public static function fromApi(array $request) : ApolAttemptDTO {
        return new self(
            id: $request['id'],
            idAvaliacao: $request['idAvaliacao'],
            idUsuario: $request['idUsuario'],
            tentativa: $request['tentativa'],
            tentativaTotal: $request['tentativaTotal'],
            nota: $request['nota'],
            status: $request['status'],
            nomeClassificacaoTipo: $request['avaliacao']['nomeClassificacaoTipo'],
            cID: $request['cIdAvaliacao'],
            cIdAvaliacaoVinculada: $request['cIdAvaliacaoVinculada']
        );
    }
}

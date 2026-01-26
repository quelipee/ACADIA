<?php
namespace App\Domain\DTOs;

use InvalidArgumentException;

readonly class ActivitiesDTO {
    public function __construct(
        public int $id,
        public int $idAvaliacao,
        public int $idUsuario,
        public string $cID,
        public int $tentativa,
        public int $tentativaTotal,
        public bool $questoesGeradas,
        public int|null $nota,
        public string $nameActivities,
        public string $nameDisciplina,
        public string $status,
        public string $acao,
        public string $nomeClassificacaoTipo,
    )
    {}

    public static function fromApi(array $request) : ActivitiesDTO {
        if(!$request['idAvaliacao']) {
            throw new InvalidArgumentException('Atividade não encontrada!!');
        }
        
        return new self(
            id: $request['id'],
            idAvaliacao: $request['idAvaliacao'],
            idUsuario: $request['idUsuario'],
            cID: $request['avaliacao']['cID'],
            tentativa: $request['tentativa'],
            tentativaTotal: $request['tentativaTotal'],
            questoesGeradas: $request['questoesGeradas'],
            nota: $request['nota'],
            nameActivities: $request['avaliacao']['nome'],
            nameDisciplina: $request['salas'][0]['nomeSalaVirtual'],
            status: $request['status'],
            acao: $request['acao'],
            nomeClassificacaoTipo: $request['avaliacao']['nomeClassificacaoTipo'],
        );
    }
}
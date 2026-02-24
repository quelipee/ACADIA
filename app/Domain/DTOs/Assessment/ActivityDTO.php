<?php
namespace App\Domain\DTOs\Assessment;

use Carbon\Carbon;
use InvalidArgumentException;

readonly class ActivityDTO {
    public function __construct(
        public int $id,
        public int $idAvaliacao,
        public int $idUsuario,
        public int $idSalaVirtual,
        public string $cID,
        public string|null $cIdAvaliacaoVinculada,
        public int $tentativa,
        public int $tentativaTotal,
        public bool $questoesGeradas,
        public int|null $nota,
        public string $nameActivities,
        public string $nameDisciplina,
        public string|null $status,
        public string|null $acao,
        public string $nomeClassificacaoTipo,
        public Carbon $dataInicio,
        public Carbon $dataFim,
    )
    {}

    public static function fromApi(array $request) : ActivityDTO {
        if(!$request['idAvaliacao']) {
            throw new InvalidArgumentException('Atividade não encontrada!!');
        }

        return new self(
            id: $request['id'],
            idAvaliacao: $request['idAvaliacao'],
            idUsuario: $request['idUsuario'],
            idSalaVirtual: $request['salas'][0]['idSalaVirtual'],
            cID: $request['cIdAvaliacao'],
            cIdAvaliacaoVinculada: $request['cIdAvaliacaoVinculada'],
            tentativa: $request['tentativa'],
            tentativaTotal: $request['tentativaTotal'],
            questoesGeradas: $request['questoesGeradas'],
            nota: $request['nota'],
            nameActivities: $request['avaliacao']['nome'],
            nameDisciplina: $request['salas'][0]['nomeSalaVirtual'],
            status: $request['status'],
            acao: $request['acao'],
            nomeClassificacaoTipo: $request['avaliacao']['nomeClassificacaoTipo'],
            dataInicio: Carbon::parse($request['dataInicio']),
            dataFim: Carbon::parse($request['dataFim'])
        );
    }
}

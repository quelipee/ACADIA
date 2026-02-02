<?php 

namespace App\Domain\DTOs\Academic;

use InvalidArgumentException;

class GraduationDTO {
    public function __construct(
        public readonly string $id,
        public readonly int $idCurso,
        public readonly string $cIdCurso,
        public readonly int $idUsuarioCurso,
        public readonly string $nome,
        public readonly ?string $turma,
        public readonly string $nomeCursoNivel
    )
    {}

    public static function fromApi(array $request) : GraduationDTO {
        
        if(!isset($request['id'])) {
                throw new InvalidArgumentException('Error id não encontrado!!');
            }

        return new self(
            id: $request['id'],
            idCurso: $request['idCurso'],
            cIdCurso: $request['cIdCurso'],
            idUsuarioCurso: $request['idUsuarioCurso'],
            nome: $request['nome'],
            turma: $request['turma'],
            nomeCursoNivel: $request['nomeCursoNivel'],
        );
    }
}
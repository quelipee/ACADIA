<?php

namespace App\Infrastructure\Http\Academic;

use App\Concerns\Assessment\HasAssessmentType;
use App\Domain\Contracts\Academic\DisciplinaClientInterface;
use App\Domain\Contracts\FaculdadeClientInterface;
use App\Domain\DTOs\Academic\DisciplinaDTO;

class Disciplina implements DisciplinaClientInterface{
    use HasAssessmentType;

    public function __construct(
        private readonly FaculdadeClientInterface $http,
    )
    {}

    public function courseDiscipline(int $idUserCourse, int $idCourse) : array { //disciplina do curso
        $endpoint = str_replace(
            '{idCourse}', $idCourse,
            config('faculdade.endpoints.list_subjects')
        );
        $response = $this->http->client()->get($endpoint,[
            'idUsuarioCurso' => $idUserCourse
        ]);
        
        foreach ($response['salaVirtuais'] as $subject) {
            $list_disciplina[] = DisciplinaDTO::fromApi($subject);
        }

        return $list_disciplina;
    }
}
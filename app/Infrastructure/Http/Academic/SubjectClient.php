<?php

namespace App\Infrastructure\Http\Academic;

use App\Concerns\Assessment\HasAssessmentType;
use App\Domain\Contracts\FaculdadeClientInterface;
use App\Domain\Contracts\Academic\SubjectClientInterface;
use App\Domain\DTOs\SubjectDTO;

class SubjectClient implements SubjectClientInterface{
    use HasAssessmentType;

    public function __construct(
        private readonly FaculdadeClientInterface $http,
    )
    {}

    public function courseSubject(int $idUserCourse, int $idCourse) : array { //disciplina do curso
        $endpoint = str_replace(
            '{idCourse}', $idCourse,
            config('faculdade.endpoints.list_subjects')
        );
        $response = $this->http->client()->get($endpoint,[
            'idUsuarioCurso' => $idUserCourse
        ]);
        
        foreach ($response['salaVirtuais'] as $subject) {
            $list_subjects[] = SubjectDTO::validatedSubject($subject);
        }

        return $list_subjects;
    }
}
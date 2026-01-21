<?php

namespace App\Infrastructure\Http\Academic;

use App\Concerns\Assessment\HasAssessmentType;
use App\Domain\Contracts\FaculdadeClientInterface;
use App\Domain\Contracts\Academic\SubjectClientInterface;
use App\Domain\DTOs\SubjectDTO;
use App\Domain\Enums\ExamActivityType;

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
    

    //TODO AQUI DA PRA MUDAR E FAZER OUTRO TRAIT PARA REUTILIZAR E PEGAR SOMENTE AS PROVAS OU AS APOL's
    // idSalaVirtualOfertaAtual É PARA SOMENTE PARA CURSOS FORA DA GRADUAÇÃO DO USUARIO, EX: CURSO DE INGLES ETC
    public function listStudentActivity(int $idSalaVirtual, int $idSalaVirtualOferta) : array {

        $eita = $this->HasAssessmentType($idSalaVirtual, $idSalaVirtualOferta, ExamActivityType::MISTA->value);
        dd($eita);
    }
}
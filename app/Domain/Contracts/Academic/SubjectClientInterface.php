<?php

namespace App\Domain\Contracts\Academic;

interface SubjectClientInterface {
    public function courseSubject(int $idUserCourse, int $idCourse) : array;
}
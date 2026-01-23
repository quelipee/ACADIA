<?php

namespace App\Domain\Contracts\Academic;

interface DisciplinaClientInterface {
    public function courseDiscipline(int $idUserCourse, int $idCourse) : array;
}
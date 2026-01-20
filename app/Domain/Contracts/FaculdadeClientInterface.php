<?php

namespace App\Domain\Contracts;

interface FaculdadeClientInterface {
    // aqui o usuario efetuara o login
    public function signInAuthenticated() : void;
    public function getInfoListGraduation() : array;
    public function client();
    public function courseMaterials(int $idUserCourse, int $idCourse);
    public function listStudentActivities(int $idSalaVirtual, int $idSalaVirtualOfertaAproveitamento) : array;
}
<?php

namespace App\Domain\Contracts\Academic;

interface GraduationClientInterface {
    public function getInfoListGraduation() : array;
}
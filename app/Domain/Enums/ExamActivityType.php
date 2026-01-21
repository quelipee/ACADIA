<?php

namespace App\Domain\Enums;

enum ExamActivityType : string {
    case APOL = 'Objetiva';
    case EXAM = 'Discursiva';
    case MISTA = 'Mista';
}
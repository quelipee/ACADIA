<?php

namespace App\Domain\Contracts;

interface FaculdadeClientInterface {
    // aqui o usuario efetuara o login
    public function signInAuthenticated() : void;
}
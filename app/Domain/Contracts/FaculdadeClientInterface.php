<?php

namespace App\Domain\Contracts;

interface FaculdadeClientInterface {
    public function signInAuthenticated() : void;
    public function client();
}
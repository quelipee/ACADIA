<?php

namespace App\Domain\Contracts;

use Illuminate\Http\Request;

interface FaculdadeClientInterface {
    public function signInAuthenticated() : void;
    public function client();
}

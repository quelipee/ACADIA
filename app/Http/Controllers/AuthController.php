<?php

namespace App\Http\Controllers;

use App\Domain\Contracts\FaculdadeClientInterface;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct(
        protected FaculdadeClientInterface $faculdade,
        // protected
    ) {}

    public function login(Request $request)
    {
        $request->validate([
            'ru' => 'required|string',
            'password' => 'required|string|min:6'
        ]);

        $status = $this->faculdade->signInAuthenticated($request);

        if($status != 200) {
            throw ValidationException::withMessages([
                'ru' => 'RU ou senha inválidos.'
            ]);
        }


        return redirect()->route('dashboard');
    }
}


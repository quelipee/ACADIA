<?php

namespace App\Infrastructure\Clients;
use App\Domain\Contracts\FaculdadeClientInterface;
use App\Infrastructure\Clients\Session\FaculdadeSession;
use Illuminate\Support\Facades\Http;

class FaculdadeHttpClient implements FaculdadeClientInterface{

    public function __construct(
        private FaculdadeSession $session
    )
    {}

    public function signInAuthenticated(): void
    {
        $response = Http::asForm()->
        post(config('faculdade.base_login'),[
            'login' => config('faculdade.credentials.login'),
            'senha' => config('faculdade.credentials.password')
        ]);
        
        // setando os cookies para poder usar depois e assim manter o login ativo do usuario
        $this->session->setCookies($response->cookies()->toArray());        
    }

}
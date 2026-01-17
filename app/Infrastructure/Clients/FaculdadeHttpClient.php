<?php

namespace App\Infrastructure\Clients;
use App\Domain\Contracts\FaculdadeClientInterface;
use App\Infrastructure\Clients\Session\FaculdadeSession;
use Exception;
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
        $this->session->setHeaders($response['usuario']);        
    }

    public function client() {
        return Http::baseUrl(config('faculdade.base_url'))
        ->withCookies(
            $this->session->getCookies(),
            parse_url(config('faculdade.base_url'), PHP_URL_HOST)
        )->withHeaders($this->session->getHeaders());
    }

    public function getInfoGraduation() : array{
        $response = $this->client()->get('ava/sistema/Curso/0/EscolaUsuario/true/?emCurso=true');
        
        if(!$response->successful()){
            throw new Exception($response->status());
        }

        return $response['cursos'];
    }

}
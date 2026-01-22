<?php

namespace App\Infrastructure\Http;
use App\Domain\Contracts\FaculdadeClientInterface;
use App\Infrastructure\Http\Session\FaculdadeSession;
use Illuminate\Support\Facades\Http;

class FaculdadeHttpClient implements FaculdadeClientInterface{

    public function __construct(
        private FaculdadeSession $session
    )
    {}

    public function signInAuthenticated(): void
    {
        $response = Http::asForm()->
        post(config('faculdade.base_urls.login'),[
            'login' => config('faculdade.credentials.login'),
            'senha' => config('faculdade.credentials.password')
        ]);

        // setando os cookies para poder usar depois e assim manter o login ativo do usuario
        $this->session->setCookies($response->cookies()->toArray());
        $this->session->setHeaders($response['usuario']);        
    }

    public function client() {
        return Http::baseUrl(config('faculdade.base_urls.api'))
        ->withCookies(
            $this->session->getCookies(),
            parse_url(config('faculdade.base_urls.api'), PHP_URL_HOST)
        )->withHeaders($this->session->getHeaders());
    }

    public function naotemnome(string $id, string $status) : array{
        $endpoint = str_replace(
            '{id}', $id,
            config('faculdade.endpoints.list_questions')
        );
        
        $response = $this->client()->get($endpoint);
        dd($response['avaliacaoUsuarioHistoricos'][0]);
    }
}

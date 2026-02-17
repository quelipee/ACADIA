<?php

namespace App\Infrastructure\Http;
use App\Domain\Contracts\FaculdadeClientInterface;
use App\Infrastructure\Http\Session\FaculdadeSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;

use function PHPUnit\Framework\isNull;

class FaculdadeHttpClient implements FaculdadeClientInterface{

    public function __construct(
        private FaculdadeSession $session
    )
    {}

    public function signInAuthenticated(Request $request)
    {
        $response = Http::asForm()->
        post(config('faculdade.base_urls.login'),[
            // 'login' => $request->ru,
            // 'senha' => $request->password
            'login' => config('faculdade.credentials.login'),
            'senha' => config('faculdade.credentials.password')
        ]);

        if (is_null($response->json())) {
            return $response->status();
        }
        // setando os cookies para poder usar depois e assim manter o login ativo do usuario
        $this->session->setCookies($response->cookies()->toArray());
        $this->session->setHeaders($response['usuario']);

        return $response->status();
    }

    public function client() {
        return Http::baseUrl(config('faculdade.base_urls.api'))
        ->withCookies(
            $this->session->getCookies(),
            parse_url(config('faculdade.base_urls.api'), PHP_URL_HOST)
        )->withHeaders($this->session->getHeaders());
    }
}

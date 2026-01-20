<?php

namespace App\Infrastructure\Clients;
use App\Domain\Contracts\FaculdadeClientInterface;
use App\Infrastructure\Clients\Session\FaculdadeSession;
use Exception;
use Illuminate\Support\Facades\Http;
use App\Domain\DTOs\GraduationDTO;

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

    public function getInfoListGraduation() : array{
        $response = $this->client()->get(config('faculdade.endpoints.list_graduation'));
        
        // TODO ADJUST ISSO DE EXCEPTION
        if(!$response->successful()){
            throw new Exception($response->status());
        }

        foreach ($response['cursos'] as $graduation) {
            $list_graduation[] = GraduationDTO::validatedGraduationCourse($graduation);
        }

        return $list_graduation; 
    }

    public function courseMaterials(int $idUserCourse, int $idCourse) {
        $endpoint = str_replace(
            '{idCourse}', $idCourse,
            config('faculdade.endpoints.list_materials')
        );
        $response = $this->client()->get($endpoint,[
            'idUsuarioCurso' => $idUserCourse
        ]);

        return $response['salaVirtuais'];
    }

    public function listStudentActivities(int $idSalaVirtual, int $idSalaVirtualOfertaAproveitamento) : array{
        $response = $this->client()->get(config('faculdade.endpoints.list_activities'),[
            'idSalaVirtual' => $idSalaVirtual,
            'idSalaVirtualOferta' => $idSalaVirtualOfertaAproveitamento,
        ]);

        return $response['avaliacaoUsuarios'];
    }

}

// paginacao/true?numRegistros=25&filtro=&ordenacao=&idSalaVirtual=80872&idSalaVirtualOferta=1005841
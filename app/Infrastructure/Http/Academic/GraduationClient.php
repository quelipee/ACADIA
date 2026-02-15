<?php

namespace App\Infrastructure\Http\Academic;

use App\Domain\Contracts\FaculdadeClientInterface;
use App\Domain\Contracts\Academic\GraduationClientInterface;
use App\Domain\DTOs\Academic\GraduationDTO;
use InvalidArgumentException;

class GraduationClient implements GraduationClientInterface{
    public function __construct(
        private readonly FaculdadeClientInterface $http,
    )
    {}

    public function getInfoListGraduation() : array {
        $response = $this->http->client()->get(config('faculdade.endpoints.list_graduation'));
        
        if(!$response->successful()){
            throw new InvalidArgumentException($response->status());
        }
        
        foreach ($response['cursos'] as $graduation) {
            $list_graduation[] = GraduationDTO::fromApi($graduation);
        }

        return $list_graduation; 
    }
}
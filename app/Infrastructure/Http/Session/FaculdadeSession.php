<?php

namespace App\Infrastructure\Http\Session;

class FaculdadeSession {
    private array $cookies = [];
    private array $headers = [];

    public function setCookies(array $cookies) : void {
        foreach ($cookies as $cookie) {
            $this->cookies[$cookie['Name']] = $cookie['Value'];
        }
    }

    public function setHeaders(array $headers): void {
        foreach ($headers as $key => $header) {
            if(is_scalar($header)) {
                $this->headers['X-' . ucfirst($key)] = $header;
            }
        }
    }

    public function getCookies() : array {
        return $this->cookies;
    }

    public function getHeaders() : array {
        return $this->headers;
    }

    public function isAuthenticated() :bool {
        return !empty($this->cookies);
    }
}
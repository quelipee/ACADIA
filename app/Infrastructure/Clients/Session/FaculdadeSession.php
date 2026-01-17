<?php

namespace App\Infrastructure\Clients\Session;

class FaculdadeSession {
    private array $cookies = [];

    public function setCookies(array $cookies) : void {
        $this->cookies = $cookies;
    }

    public function getCookies() : array {
        return $this->cookies;
    }

    public function isAuthenticated() :bool {
        return !empty($this->cookies);
    }
}
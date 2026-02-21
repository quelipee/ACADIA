<?php

namespace App\Infrastructure\Http\Session;

use App\Domain\DTOs\Profile\UserProfileDTO;
use Illuminate\Support\Facades\Session;

class FaculdadeSession {
    private array $cookies = [];
    private array $headers = [];

    private const SESSION_KEY_COOKIES = 'faculdade_cookies';
    private const SESSION_KEY_HEADERS = 'faculdade_headers';

    public function setCookies(array $cookies) : void {
        foreach ($cookies as $cookie) {
            $this->cookies[$cookie['Name']] = $cookie['Value'];
        }
        Session::put(self::SESSION_KEY_COOKIES, $this->cookies);
    }

    public function setHeaders(array $headers): void {
        foreach ($headers as $key => $header) {
            if(is_scalar($header)) {
                $this->headers['X-' . ucfirst($key)] = $header;
            }
        }
        Session::put(self::SESSION_KEY_HEADERS, $this->headers);
    }

    public function getCookies() : array {
        return Session::get(self::SESSION_KEY_COOKIES, []);
    }

    public function getHeaders() : array {
        return Session::get(self::SESSION_KEY_HEADERS, []);
    }

    public function isAuthenticated() :bool {
        return !empty($this->getHeaders());
        // return !empty($this->cookies);
    }

    public function getUser() : ?array{
        if (!$this->isAuthenticated()) {
            return null;
        }

        $headers = $this->getHeaders();

        return UserProfileDTO::fromArray($headers)->toArray();
    }

    public function logout(): void {
        Session::forget([
            self::SESSION_KEY_COOKIES,
            self::SESSION_KEY_HEADERS
        ]);
    }
}

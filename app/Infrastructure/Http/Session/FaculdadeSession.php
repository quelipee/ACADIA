<?php

namespace App\Infrastructure\Http\Session;

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
        // return $this->cookies;
    }

    public function getHeaders() : array {
        return Session::get(self::SESSION_KEY_HEADERS, []);
        // return $this->headers;
    }

    public function isAuthenticated() :bool {
        return !empty($this->getCookies());
        // return !empty($this->cookies);
    }

    public function logout(): void {
        Session::forget([
            self::SESSION_KEY_COOKIES,
            self::SESSION_KEY_HEADERS
        ]);
    }
}

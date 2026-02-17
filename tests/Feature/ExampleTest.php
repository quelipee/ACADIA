<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Http\Response;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_login() {
        $data = [
            'ru' => "4697345",
            'password' => "493614"
        ];

        $response = $this->post('/login-faculdade',$data);
        $response->assertStatus(302);
    }
}

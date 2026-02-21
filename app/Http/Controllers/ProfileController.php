<?php

namespace App\Http\Controllers;

use App\Domain\DTOs\Profile\UserProfileDTO;
use App\Infrastructure\Http\Session\FaculdadeSession;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function __construct(
        protected FaculdadeSession $session,
    )
    {}

    public function profile () {
        $user = $this->session->getUser();

        return Inertia::render('Profile', [
            'user' => $user,
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Domain\Contracts\Academic\GraduationClientInterface;
use App\Infrastructure\Http\Session\FaculdadeSession;
use Inertia\Inertia;

class GraduationController extends Controller
{
    public function __construct(
        protected GraduationClientInterface $graduation,
        protected FaculdadeSession $session
    )
    {}

    public function list_all_graduation() {
        $graduationData = $this->graduation->getInfoListGraduation();
        return Inertia::render('Dashboard', ['graduation' => $graduationData]);
    }
}

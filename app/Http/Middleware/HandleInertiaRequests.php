<?php

namespace App\Http\Middleware;

use App\Domain\DTOs\Profile\UserProfileDTO;
use App\Infrastructure\Http\Session\FaculdadeSession;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $session = app(FaculdadeSession::class);
        $user = $session->getUser();
        
        return [
            ...parent::share($request),

            'auth' => [
                'user' => $user,
                'isAuthenticated' => $session->isAuthenticated()
            ]
        ];
    }
}

<?php

namespace App\Http\Middleware;

use App\Infrastructure\Http\Session\FaculdadeSession;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureFaculdadeAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $session = app(FaculdadeSession::class);

        if (!$session->isAuthenticated()) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}

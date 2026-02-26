<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureActivityNotExpired
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $endDate = Carbon::parse($request['data']['dataFim']);
        $nowDate = Carbon::now();
        $try = (int) $request['data']['tentativa'];
        $tryTotal = (int) $request['data']['tentativaTotal'];

        $isExpired = $endDate->lessThanOrEqualTo($nowDate);
        $triesExceeded = $try > $tryTotal;
        $isFinalized = ($request['data']['status'] === 'Finalizada' || $request['data']['acao'] === 'Gabarito');

        if ($isExpired || $isFinalized || $triesExceeded) {

            return redirect()->route('activities', [
                'id' => $request['data']['idSalaVirtual'],
                'idSalaVirtual' => $request['idSalaVirtualOfertaAproveitamento'],
                'type' => $request['data']['nomeClassificacaoTipo'],
            ]);
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use App\Http\Controllers\StoklpgController;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CekResetStok
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        app(StoklpgController::class)->resetOtomatis();

        return $next($request);
    }
}

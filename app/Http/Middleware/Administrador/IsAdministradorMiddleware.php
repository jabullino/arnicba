<?php

namespace App\Http\Middleware\Administrador;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class IsAdministradorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || Auth::user()->cargo_id !== 4) {
            // No autenticado o no es administrador → redirige a inicio
            return redirect()->route('inicio');
        }

        // Usuario autenticado y con cargo_id = 4 → continua
        return $next($request);
    }
}

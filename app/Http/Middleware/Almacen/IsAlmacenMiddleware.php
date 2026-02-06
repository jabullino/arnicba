<?php

namespace App\Http\Middleware\Almacen;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class IsAlmacenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         $user = Auth::user();

        if (!$user || $user->cargo_id !== 10) {
            // Usuario no autorizado
            abort(403, 'Acceso denegado.');
        }
         return $next($request);
    }
}




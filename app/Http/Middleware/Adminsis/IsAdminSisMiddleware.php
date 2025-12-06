<?php

namespace App\Http\Middleware\Adminsis;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdminSisMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user || $user->cargo_id !== 1) {
            // Usuario no autorizado
            abort(403, 'Acceso denegado.');
        }

        return $next($request);
    }
}

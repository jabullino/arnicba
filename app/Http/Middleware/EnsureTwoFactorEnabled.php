<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class EnsureTwoFactorEnabled
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        // Si está autenticado y NO tiene 2FA activado, lo mandamos a configurar
        if ($user && !$user->two_factor_secret && !$request->is('two-factor-setup*')) {
            return redirect()->route('two-factor.setup');
        }

        return $next($request);
    }
}

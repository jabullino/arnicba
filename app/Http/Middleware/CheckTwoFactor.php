<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckTwoFactor
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user) {
            // Si no tiene 2FA activado → redirige a la configuración
            if (empty($user->two_factor_secret) && !$request->is('two-factor-setup')) {
                return redirect()->route('two-factor.setup');
            }

            // Si tiene 2FA activado pero aún no verificó en esta sesión → pedir TOTP
            if ($user->two_factor_secret && !$request->session()->get('twofactor_passed', false)) {
                if (!$request->is('two-factor-verify') && !$request->is('two-factor-setup')) {
                    return redirect()->route('two-factor.verify');
                }
            }
        }

        return $next($request);
    }
}

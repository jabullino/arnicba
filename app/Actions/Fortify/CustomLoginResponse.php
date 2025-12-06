<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Support\Facades\Auth;

class CustomLoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = $request->user();

        if (is_null($user->two_factor_secret) || is_null($user->two_factor_confirmed_at)) {
            Auth::logout();
            session(['two_factor.id' => $user->id]);

            return redirect()->route('two-factor.setup')
;
        }

        return redirect()->intended(config('fortify.home'));
    }
}

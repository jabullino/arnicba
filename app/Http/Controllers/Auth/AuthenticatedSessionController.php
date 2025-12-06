<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;
use PragmaRX\Google2FA\Google2FA;
use App\Models\User;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
   public function store(Request $request)
{

    if(User::count() === 0){
        // Redirigir al primer registro
        return redirect()->route('primerRegistro'); 
        // Asegúrate de tener definida esta ruta apuntando a:
        // resources/views/AdminSis/UserAdminSis/register.blade.php
    }

    $credentials = $request->validate([
        'email' => ['required','email'],
        'password' => ['required','string']
    ]);

    if(!Auth::attempt($credentials, $request->boolean('remember'))){
        return back()->withErrors(['email'=>'Credenciales inválidas']);
    }

    $request->session()->regenerate();
    $user = Auth::user();

    // Si NO tiene 2FA → QR
    if(empty($user->two_factor_secret)){
        return redirect()->route('two-factor.setup');
    }

    // Si tiene 2FA → pedir código
    return redirect()->route('two-factor.verify');
}
    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}

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
    public function create()
    {
        if (User::count() === 0) {
            return redirect()->route('CreaAdminSis');
        }

        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
public function store(Request $request)
{
    if (User::count() === 0) {
        return redirect()->route('primerRegistro');
    }

    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required', 'string']
    ]);

    // 🔹 Buscar usuario primero
    $user = User::where('email', $credentials['email'])->first();

    // 🔴 Si existe pero está inactivo
    if ($user && $user->status !== 'Activo') {
        return back()->withErrors([
            'email' => 'Usuario inactivo o bloqueado'
        ]);
    }

    // 🔐 Intento de login normal
    if (!Auth::attempt($credentials, $request->boolean('remember'))) {
        return back()->withErrors([
            'email' => 'Credenciales inválidas'
        ]);
    }

    $request->session()->regenerate();
    $user = Auth::user();

    // 🔹 2FA
    if (empty($user->two_factor_secret)) {
        return redirect()->route('two-factor.setup');
    }

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

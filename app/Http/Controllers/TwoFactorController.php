<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FAQRCode\Google2FA;
use App\Models\User;

class TwoFactorController extends Controller
{
    protected $google2fa;

    public function __construct()
    {
        $this->google2fa = new Google2FA();
    }

    // Mostrar QR para activar 2FA
    public function show()
    {
        $user = Auth::user();

        // Si ya tiene 2FA → ir al dashboard
        if (!empty($user->two_factor_secret)) {
            return redirect()->route('dashboard');
        }

        // Generar secreto y QR
        $secret = $this->google2fa->generateSecretKey();
        $QR_Image = $this->google2fa->getQRCodeInline(
            config('app.name'),
            $user->email,
            $secret
        );

        // Guardar temporalmente en sesión
        session(['two_factor_secret' => $secret]);

        return view('two-factor.setup', [
            'QR_Image' => $QR_Image,
            'secret' => $secret
        ]);
    }

    // Confirmar TOTP y activar 2FA
    public function store(Request $request)
    {
        $request->validate([
            'totp' => 'required',
        ]);

        $user = User::findOrFail(Auth::id()); 
        $secret = session('two_factor_secret');

        if ($this->google2fa->verifyKey($secret, $request->totp)) {
            $user->two_factor_secret = $secret;
           

            $user->save();



            session()->forget('two_factor_secret');
            session(['twofactor_passed' => true]);

            return redirect()->route('dashboard')->with('success', '2FA activado correctamente.');
        }

        return back()->withErrors(['totp' => 'Código inválido']);
    }

    // Página para ingresar TOTP después del login si ya tiene 2FA
    public function verify()
    {
        return view('two-factor.verify');
    }

    // Confirmar TOTP si ya tenía 2FA
    public function verifyPost(Request $request)
    {
        $request->validate(['totp' => 'required']);

        $user = Auth::user();

        if ($this->google2fa->verifyKey($user->two_factor_secret, $request->totp)) {
            session(['twofactor_passed' => true]);
            return redirect()->route('dashboard');
        }

        return back()->withErrors(['totp' => 'Código inválido']);
    }
}

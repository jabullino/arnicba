<!-- resources/views/profile/2fa.blade.php -->
@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-10 p-6 bg-white rounded shadow">
    <h2 class="text-xl font-semibold mb-4">Configuración de Autenticación en Dos Pasos (2FA)</h2>

    <p class="mb-4">
        Escanea el siguiente código QR con tu aplicación de Google Authenticator o Authy.
    </p>

    <div class="mb-6 text-center">
        <!-- QR generado por TwoFactorController -->
        {!! $qrCode !!}
    </div>

    <p class="mb-4">
        Luego, ingresa el código de 6 dígitos generado por tu app para activar la verificación en dos pasos.
    </p>

    <form method="POST" action="{{ route('profile.2fa.confirm') }}">
        @csrf

        <div class="mb-4">
            <label for="code" class="block font-medium text-gray-700">Código TOTP</label>
            <input type="text" name="code" id="code" value="{{ old('code') }}" required autofocus
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            @error('code')
                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit"
                class="w-full py-2 px-4 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700">
            Confirmar Código
        </button>
    </form>
</div>
@endsection

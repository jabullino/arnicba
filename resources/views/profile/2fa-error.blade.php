@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-10 p-6 bg-white rounded shadow text-center">
    <h2 class="text-xl font-semibold mb-4">Acceso Denegado</h2>
    <p class="mb-4">Debes iniciar sesión primero para configurar la verificación en dos pasos (2FA).</p>
    <a href="{{ route('inicio') }}" class="py-2 px-4 bg-indigo-600 text-white rounded hover:bg-indigo-700">
        Ir al login
    </a>
</div>
@endsection

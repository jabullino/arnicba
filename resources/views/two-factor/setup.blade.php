<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configura tu 2FA</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white rounded-2xl shadow-lg p-8 max-w-md w-full text-center">
        <h1 class="text-2xl font-bold mb-6">Configura la Verificación en Dos Pasos</h1>

        <p class="mb-4 text-gray-700">
            Escanea este QR con tu aplicación de autenticación (Google Authenticator, Authy, etc.)
        </p>

        <!-- QR Code SVG -->
        <div class="mb-6 flex justify-center">
            {!! $QR_Image !!}
        </div>

        <p class="mb-4 text-gray-700">Luego, introduce el código que genere tu app:</p>

        <form method="POST" action="{{ route('two-factor.store') }}" class="flex flex-col items-center gap-4">
            @csrf
            <input type="text" name="totp" placeholder="Código TOTP" required
                class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">

            <button type="submit"
                class="bg-blue-600 text-white font-semibold py-3 px-6 rounded-lg hover:bg-blue-700 transition">
                Activar 2FA
            </button>

            @if ($errors->any())
                <div class="text-red-500 mt-2">
                    {{ $errors->first() }}
                </div>
            @endif

            @if(session('success'))
                <div class="text-green-600 mt-2">
                    {{ session('success') }}
                </div>
            @endif
        </form>
    </div>

</body>
</html>

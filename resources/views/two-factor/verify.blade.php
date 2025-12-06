<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación 2FA</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 flex items-center justify-center min-h-screen text-white">

    <div class="bg-gray-800 rounded-2xl shadow-lg p-8 max-w-md w-full text-center">
        <h1 class="text-2xl font-bold mb-6">Verificación en Dos Pasos</h1>

        <p class="mb-4 text-gray-300">
            Ingresa el código generado por tu aplicación de autenticación.
        </p>

        <form method="POST" action="{{ route('two-factor.verify.post') }}" class="flex flex-col items-center gap-4">
            @csrf
            <input type="text" name="totp" placeholder="Código TOTP" required
                class="w-full p-3 border border-gray-600 rounded-lg bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">

            <button type="submit"
                class="bg-blue-600 text-white font-semibold py-3 px-6 rounded-lg hover:bg-blue-700 transition">
                Verificar
            </button>

            @if ($errors->any())
                <div class="text-red-400 mt-2">
                    {{ $errors->first() }}
                </div>
            @endif
        </form>
    </div>

</body>
</html>

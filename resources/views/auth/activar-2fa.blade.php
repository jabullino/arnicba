<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            Activar Verificación en Dos Pasos
        </h2>
    </x-slot>

    <div class="py-6 px-4 max-w-xl mx-auto">

        {{-- Estado inicial: no tiene secreto --}}
        @if (! auth()->user()->two_factor_secret)
            <p class="mb-4">Haz clic en el botón para activar la verificación en dos pasos.</p>

            <form method="POST" action="/user/two-factor-authentication">
                @csrf
                <x-primary-button>Activar 2FA</x-primary-button>
            </form>

        {{-- Tiene secreto pero aún no confirmó --}}
        @elseif (auth()->user()->two_factor_secret && ! auth()->user()->two_factor_confirmed_at)
            <p class="mb-4">Escanea este código QR con Google Authenticator y luego ingresa el código generado:</p>

            <div class="mb-6">
                {!! auth()->user()->twoFactorQrCodeSvg() !!}
            </div>

            <form method="POST" action="/user/confirmed-two-factor-authentication">
                @csrf
                <div class="mb-4">
                    <label for="code" class="block font-medium text-sm text-gray-700">Código</label>
                    <input id="code" name="code" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                </div>

                <x-primary-button>Confirmar Código</x-primary-button>
            </form>

        {{-- Ya confirmado --}}
        @elseif (auth()->user()->two_factor_confirmed_at)
            <p class="mb-4 text-green-600">✅ Tienes habilitada la verificación en dos pasos.</p>

            <form method="POST" action="/user/two-factor-authentication">
                @csrf
                @method('DELETE')
                <x-danger-button>Desactivar 2FA</x-danger-button>
            </form>
        @endif

        <div class="mt-6">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-secondary-button>Volver al login</x-secondary-button>
            </form>
        </div>
    </div>
</x-app-layout>

<x-app-layout> <x-slot name="header">
        <h2 class="font-semibold text-xl">Configurar 2FA</h2>
    </x-slot>
    <div class="p-6">
        <p>Escanea este código QR en Google Authenticator o Authy:</p> <img
            src="https://chart.googleapis.com/chart?cht=qr&chs=200x200&chl={{ urlencode($qrCodeUrl) }}" alt="QR Code">
        <p class="mt-4">Clave manual: <strong>{{ $secret }}</strong></p>
    </div>
</x-app-layout>

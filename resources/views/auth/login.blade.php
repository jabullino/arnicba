<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    @if (session('CredencialesNoValidas'))
        <div class="mb-4 font-medium text-sm text-red-600">
            {{ session('CredencialesNoValidas') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required
                autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Código 2FA -->
        <div class="mt-4"> <x-input-label for="totp" :value="__('Código 2FA (si aplica)')" />
            <x-text-input id="totp" class="block mt-1 w-full" type="text" name="totp" autocomplete="off" />
            <x-input-error :messages="$errors->get('totp')" class="mt-2" />
        </div>
        <div class="flex items-center justify-end mt-4">      
              @if (Route::has('password.request'))
                    <a class="underline text-sm text-white hover:text-gray-900 rounded-md focus:outline-none"
                       href="{{ route('password.request') }}">
                        {{ __('Olvidaste tu contraseña?') }}
                    </a>
                @endif
            <x-primary-button class=" mt-1 w-full text-center  ">
                {{ __('Registrarse') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>

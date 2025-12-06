<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
         $this->registerPolicies();

    Gate::define('is_adminSis', function ($user) {
        return $user->cargo_id === 1;
    });
    Gate::define('is_administrador', function ($user) {
        return $user->cargo_id === 4; // tu cargo de administrador
    });
    }
}

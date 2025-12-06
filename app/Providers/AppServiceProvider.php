<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('is_adminSis', function ($user) {
        return $user->cargo_id === 1;
    });

     Gate::define('is_administrador', function ($user) {
       return $user->cargo_id === 4;
    });
     
    Gate::define('is_tsocial', function ($user) {
       return $user->cargo_id === 7;
    });

    }
}

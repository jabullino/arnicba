<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Pagination\Paginator;
use App\Models\DetalleIngreso;
use App\Observers\DetalleIngresoObserver;
use App\Models\EgresoDetalle;
use App\Models\Lote;

use App\Observers\EgresoDetalleObserver;

use App\Observers\LoteObserver;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::define('is_adminSis', fn ($user) => $user->cargo_id === 1);
        Gate::define('is_administrador', fn ($user) => $user->cargo_id === 4);
        Gate::define('is_tsocial', fn ($user) => $user->cargo_id === 7);
        Gate::define('is_almacen', fn ($user) => $user->cargo_id === 10);

        Paginator::useBootstrap();

        DetalleIngreso::observe(DetalleIngresoObserver::class);
        EgresoDetalle::observe(EgresoDetalleObserver::class);
        Lote::observe(LoteObserver::class);
    }
}
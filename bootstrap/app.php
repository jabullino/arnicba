<?php

use App\Http\Middleware\Administrador\IsAdministradorMiddleware;
use App\Http\Middleware\Adminsis\IsAdminSisMiddleware;
use App\Http\Middleware\Tsocial\IsTsocialMiddleware;
use App\Http\Middleware\Almacen\IsAlmacenMiddleware;
use App\Http\Middleware\Adminsis\OcultaRutaMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Gate;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__ . '/../routes/console.php',
        using: function () {

            // Rutas web generales
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            // Rutas AdminSis: web + auth + 2FA
            Route::middleware(['web', 'auth', 'twofactor'])
                ->prefix('AdminSis')
                ->group(base_path('routes/adminsis.php'));

            // Rutas Administrador: web + auth (agrega 2FA si es necesario)
            Route::middleware(['web', 'auth'])
                ->prefix('Administrador')
                ->group(base_path('routes/administrador.php'));
            
            Route::middleware(['web', 'auth'])
                ->prefix('TSocial')
                ->group(base_path('routes/tsocial.php'));
             
            Route::middleware(['web', 'auth'])
                ->prefix('Almacen')
                ->group(base_path('routes/almacen.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'IsAdminSis' => IsAdminSisMiddleware::class,
            'IsAdministrador' => IsAdministradorMiddleware::class,
            'IsTsocial' => IsTsocialMiddleware::class,
            'IsAlmacen' => IsAlmacenMiddleware::class,
            'twofactor' => \App\Http\Middleware\CheckTwoFactor::class,
            
        ]);

        $middleware->redirectGuestsTo('/inicio');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();

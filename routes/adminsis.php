<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Provincia;

// Controllers
use App\Http\Controllers\CargoController;
use App\Http\Controllers\UserAdminSisController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\PanelAdminSisController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\RegistraPersonalController;
use App\Http\Controllers\PersonalController;
use App\Http\Controllers\CuentaBancoController;
use App\Http\Controllers\MovimientoCuentaController;
use App\Http\Controllers\PanelGestionesController;
use App\Http\Controllers\SegundoSemestreController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\CajaChicaController;
use App\Http\Controllers\ConsolidaCajaChicaController;
use App\Http\Controllers\EntregasCajaChicaController;
use App\Http\Controllers\ObtenCuentasController;
use App\Http\Controllers\GestionController;
use App\Http\Controllers\CierreGestionController;

Route::middleware(['auth', 'IsAdminSis'])->group(function () {

    // Panel principal
    Route::get('PanelAdminSis', [PanelAdminSisController::class, 'index'])->name('PanelAdminSis');

    // Usuarios
    Route::resource('Usuarios', UsuariosController::class)->names('Usuarios');
    Route::post('RegistraAdminSis', [UserAdminSisController::class, 'saveadminsis'])->name('RegistraAdminSis');

    // Provincias por ciudad
    Route::get('/provincias/{ciudad_id}', function ($ciudad_id) {
        $provincias = Provincia::where('ciudad_id', $ciudad_id)->get();
        return response()->json($provincias);
    });

    // Documentos
    Route::resource('Documentos', DocumentoController::class)->names('Documentos');

    // Cargos
    Route::resource('Cargos', CargoController::class)->names('Cargos');

    // Personal
    Route::get('Personal', [PersonalController::class, 'index'])->name('Personal');
   Route::post('personal/{id}', [PersonalController::class, 'store'])->name('personal.store');
    Route::get('personal/{id}/edit', [PersonalController::class, 'edit'])->name('personal.edit');
    Route::patch('personal/{id}', [PersonalController::class, 'update'])->name('personal.update');
    Route::delete('personal/{id}', [PersonalController::class, 'destroy'])->name('personal.destroy');

    // Personal pendiente y registro
    Route::get('PersonalPendiente', [RegistraPersonalController::class, 'index'])->name('PersonalPendiente');
    Route::get('PreparaPersonal/{usrId}', [RegistraPersonalController::class, 'preparapersonal'])->name('PreparaPersonal');
    Route::post('Registrapersonal', [RegistraPersonalController::class, 'registrapersonal'])->name('Registrapersonal');

    // Cuenta bancaria
    Route::resource('CuentaBanco', CuentaBancoController::class)->names('CuentaBanco');
    Route::post('/movimientos/importar', [MovimientoCuentaController::class, 'importar'])->name('movimientos.importar');
    Route::get('formcvs', [MovimientoCuentaController::class, 'index'])->name('formcvs');

    // Gestiones y segundo semestre
    Route::resource('PanelGestiones', PanelGestionesController::class)->names('PanelGestiones');
    Route::resource('SegundoSemestre', SegundoSemestreController::class)->names('SegundoSemestre');

    // Envío de correos
    Route::get('enviar-correos', [MailController::class, 'index'])->name('emails.index');
    Route::post('enviar-correos', [MailController::class, 'send'])->name('emails.send');

    // Caja Chica
    Route::resource('cajachicas', CajaChicaController::class);
    Route::resource('entregascajachicas', EntregasCajaChicaController::class);
    
    Route::get('entregascajachicas/filtro', [EntregasCajaChicaController::class, 'filtro'])
        ->name('entregascajachicas.filtro');
    Route::get('entregascajachicas/filtrar', [EntregasCajaChicaController::class, 'filtrar'])
        ->name('entregascajachicas.filtrar')
        ->middleware('can:is_adminSis');
    Route::get('cajachicas/por-gestion/{gestion}', [CajaChicaController::class, 'porGestion']);
    Route::get('entregascajachicas/filtrar', [EntregasCajaChicaController::class, 'filtrar']);

    //Consolidación de caja chica

     Route::resource('consolidarcajachica', ConsolidaCajaChicaController::class)->names('consolidarcajachica');
     Route::get('cuentas-por-banco/{bancoId}', [ObtenCuentasController::class, 'obtenerCuentas']);
     Route::resource('Gestiones',GestionController::class)->names('Gestiones');
     Route::post('cierraGestion',[CierreGestionController::class,'cierraGestion'])->name('cierraGestion');
     Route::post('reabreGestion',[CierreGestionController::class,'reabreGestion'])->name('reabreGestion');
     Route::get('FormReabreGestion',[CierreGestionController::class,'index'])->name('FormReabreGestion');



});

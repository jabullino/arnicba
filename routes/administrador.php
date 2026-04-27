<?php

use App\Http\Controllers\AsientoController;
use App\Http\Controllers\EmpleadosController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PanelAdministradorController;
use App\Http\Controllers\PanelConsultasController;
use App\Http\Controllers\TipoCambioController;
use App\Models\TipoCambioCompra;
use App\Http\Controllers\TcController;
use App\Http\Controllers\PagaServiciosController;
use App\Http\Controllers\PagaSueldosController;
use App\Models\SubCuenta;
use App\Http\Controllers\BancosController;
use App\Http\Controllers\CuentaBancoController;
use App\Http\Controllers\GastosCajaChicaController;
use App\Http\Controllers\ImprimeEstadoResultadosController;
use App\Http\Controllers\MovimientoCuentaController;
use App\Http\Controllers\ObtenCuentasController;
use App\Models\CuentaBancos;
use App\Http\Controllers\ReporteMensualController;
use App\Http\Controllers\PersonalController;
use App\Http\Controllers\RegistraPersonalController;
use App\Http\Controllers\PagaSueldoIndividualController;
use App\Http\Controllers\PermisoVacacionesController;
use App\Http\Controllers\VacacionController;
use App\Http\Controllers\ImprimeBoletasPagoController;
use App\Http\Controllers\EntregasCajaChicaController;
use App\Http\Controllers\AdministradorEntregasController;
use App\Http\Controllers\ImpresionIngresosEgresosController;
use App\Http\Controllers\ProductoEscolarController;
use App\Http\Controllers\IngresoEscolarController;
use App\Http\Controllers\EgresosEscolarController;
use App\Http\Controllers\ImpresionIngresosEgresosEscolarController;
use App\Http\Controllers\BalanceCajaChicaController;





Route::middleware(['auth', 'IsAdministrador'])->group(function () {
    Route::get('PanelAdministrador', [PanelAdministradorController::class, 'index'])->name('admin.paneldecontrol');
    Route::resource('Asientos', AsientoController::class)->names('Asientos');
    Route::get('PanelContabilidad', function () {
        return view('Administrador.PanelContabilidad');
    })->name('PanelContabilidad');
    Route::get('PanelConsultasContabilidad', [PanelConsultasController::class, 'getPanelConsutasContabilidad'])->name('PanelConsultasContabilidad');
    Route::post('VerificaOpcionConsulta', [PanelConsultasController::class, 'verificaOpcion'])->name('VerificaOpcionConsulta');
    Route::get('ConsultaCuentaPeriodo', [PanelConsultasController::class, 'getConsultaGeneralCuentaSubcuenta'])->name('ConsultaCuentaPeriodo');
    Route::get('FormPagaServicios', [PagaServiciosController::class, 'index'])->name('FormPagaServicios');
    Route::post('/pagaservicios', [PagaServiciosController::class, 'guardar'])->name('pagaservicios');
    Route::get('api/options', function () {
        $subcuentas = SubCuenta::where('cuenta_id', '1')->get();
        return response()->json($subcuentas);
    })->name('api/options');
    Route::post('/formpagasueldos', [PagaSueldosController::class, 'pagaSueldos'])->name('formpagasueldos');
    Route::post('/formpagasueldosasiento', [PagaSueldosController::class, 'pagaSueldos'])->name('PagaSueldos');
    Route::get('cuentas-por-banco/{bancoId}', [ObtenCuentasController::class, 'obtenerCuentas']);
    Route::get('FormReporteMensual', [ReporteMensualController::class, 'index'])->name('FormReporteMensual');
    Route::post('muestrareportemensual', [ReporteMensualController::class, 'muestrareportemensual'])->name('muestrareportemensual');
    Route::get('imprimirEstadoDolares', [ImprimeEstadoResultadosController::class, 'imprimirEstadoDolares'])
        ->name('imprimirEstadoDolares');
    Route::get('imprimirEstadoBolivianos', [ImprimeEstadoResultadosController::class, 'imprimirEstadoBolivianos'])
        ->name('imprimirEstadoBolivianos');
    Route::resource('PagoSueldoIndividual', PagaSueldoIndividualController::class)->names('PagoSueldoIndividual');
    Route::get('muestraformfechasueldos', [PagaSueldosController::class, 'muestraformfecha'])->name('muestraformfechasueldos');
    Route::post('seleccionfechaasueldos', [PagaSueldosController::class, 'seleccionaSueldos'])->name('seleccionfechaasueldos');
    Route::get('asignavacaciones', [VacacionController::class, 'index'])->name('asignavacaciones');
    //Route::post('generavacaciones', [VacacionController::class, 'asignaVacacion'])->name('generavacaciones');
    Route::get('solicitavacaciones', [PermisoVacacionesController::class, 'index'])->name('solicitavacaciones');
    Route::post('autorizaVacaciones', [PermisoVacacionesController::class, 'guardaPermisoVacaciones'])->name('autorizaVacaciones');
    Route::get('eligefechapagoboletas', [ImprimeBoletasPagoController::class, 'index'])->name('eligefechapagoboletas');
    Route::post('creaboletas', [ImprimeBoletasPagoController::class, 'extraeboletas'])->name('creaboletas');
    Route::resource('gastoscajachica', GastosCajaChicaController::class)->names('gastoscajachica');
     Route::get('listaEntregascajachica', [AdministradorEntregasController::class, 'index'])
        ->name('listaEntregascajachica');
     Route::get('listaEntregascajachica.lista', [AdministradorEntregasController::class, 'ajaxTotales'])
        ->name('listaEntregascajachica.lista');
    Route::get('balancecajachica',[BalanceCajaChicaController::class,'resumen'])
    ->name('balancecajachica');  

        //=====RUTAS MATERIAL ESCOLAR =====

        Route::resource('ProductoEscolar',ProductoEscolarController::class)->names('ProductoEscolar');
        Route::resource('IngresoEscolar',IngresoEscolarController::class)->names('IngresoEscolar');
        Route::get('/buscar-item-escolar', [ProductoEscolarController::class, 'buscarItemEscolar'])
    ->name('buscar.item.escolar');
        Route::resource('EgresosEscolar',EgresosEscolarController::class)->names('EgresosEscolar');
        Route::get('/ingresosescolar/pdf/{id}', [IngresoEscolarController::class, 'pdfIngresoEscolar'])
    ->name('ingresosescolar.pdf');
    Route::get(
        '/impresion/ingreso/{id}/{indice?}',
        [ImpresionIngresosEgresosEscolarController::class, 'flujoImpresionIngreso']
    )->name('escolar.impresion.ingreso.flujo');
    Route::get(
        '/impresion/egreso/{id}/{indice?}',
        [ImpresionIngresosEgresosEscolarController::class, 'flujoImpresionEgreso']
    )->name('escolar.impresion.egreso.flujo');
   
    Route::get('ListaIngresos', [IngresoEscolarController::class, 'listarIngresos'])->name('ListaIngresos');
    Route::get('ListaEgresos', [EgresosEscolarController::class, 'listarEgresos'])->name('ListaEgresos');
   
    
});

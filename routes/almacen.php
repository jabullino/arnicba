<?php


use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\PanelAlmacenController;
use App\Http\Controllers\LoteController;
use App\Http\Controllers\EgresosController;
use App\Http\Controllers\ImpresionIngresosEgresosController;
use App\Http\Controllers\IngresosController;



Route::middleware(['auth', 'IsAlmacen'])->group(function () {
  
Route::get('PanelAlmacen',[PanelAlmacenController::class,'index'])->name('PanelAlmacen');
Route::resource('Producto',ProductoController::class)->names('Producto');
Route::resource('Lote',LoteController::class)->names('Lote');
Route::resource('Egresos',EgresosController::class)->names('Egresos');
Route::get('/egresos/{id}/print', [EgresosController::class, 'print'])
    ->name('egresos.print');
Route::get('/egresos/{id}/pdf', [EgresosController::class, 'pdf'])
    ->name('egresos.pdf');
Route::get('/ingresos/pdf/{id}', [IngresosController::class, 'pdfIngreso'])
    ->name('ingresos.pdf');
Route::get('/Almacen/ListaIngresos', [IngresosController::class, 'listarIngresos'])
    ->name('almacen.listaIngresos');
Route::get('/Almacen/ListaEgresos', [EgresosController::class, 'listarEgresos'])
    ->name('almacen.listaEgresos');
Route::get('lineaingreso',[ImpresionIngresosEgresosController::class, 'lineaIngreso'])->name('lineaingreso');
Route::get('lineaegreso',[ImpresionIngresosEgresosController::class, 'lineaEgreso'])->name('lineaegreso');
Route::get('imprimir-egreso',[ImpresionIngresosEgresosController::class, 'imprimirLineaEgreso'])->name('imprimir-egreso');
Route::get('imprimir-ingreso',[ImpresionIngresosEgresosController::class, 'imprimirLineaIngreso'])->name('imprimir-ingreso');
Route::resource('Ingresos',IngresosController::class)->names('Ingresos');
Route::get('imprimirIngresos/{id}', 
    [ImpresionIngresosEgresosController::class, 'contraldorImpresionIngresos']
)->name('imprimirIngresos');
Route::get('imprimirEgresos/{id}', 
    [ImpresionIngresosEgresosController::class, 'contraldorImpresionEgresos']
)->name('imprimirEgresos');

Route::get(
        '/impresion/ingreso/{id}/{indice?}',
        [ImpresionIngresosEgresosController::class, 'flujoImpresionIngreso']
    )->name('almacen.impresion.ingreso.flujo');

Route::get(
        '/impresion/egreso/{id}/{indice?}',
        [ImpresionIngresosEgresosController::class, 'flujoImpresionEgreso']
    )->name('almacen.impresion.egreso.flujo');

    Route::get(
        '/impresion/ingreso/{id}/{indice}/print',
        [ImpresionIngresosEgresosController::class, 'imprimirProductoIngreso']
    )->name('almacen.impresion.ingreso.imprimir');

    Route::get(
        '/impresion/ingreso/{id}/{indice}/print',
        [ImpresionIngresosEgresosController::class, 'imprimirProductoEgreso']
    )->name('almacen.impresion.egreso.imprimir');

});

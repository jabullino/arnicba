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
Route::get('ListaIngresos', [IngresosController::class, 'listarIngresos'])->name('ListaIngresos');
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
Route::get('ListaEgresos', [EgresosController::class, 'listarEgresos'])->name('ListaEgresos');
  
});

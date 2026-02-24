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

Route::get('/kardex/prueba',[ImpresionIngresosEgresosController::class, 'imprimirCabecera'])->name('kardex.imprimir');
Route::resource('Ingresos',IngresosController::class)->names('Ingresos');
  
});

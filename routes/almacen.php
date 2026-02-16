<?php


use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\PanelAlmacenController;
use App\Http\Controllers\LoteController;
use App\Http\Controllers\EgresosController;

Route::middleware(['auth', 'IsAlmacen'])->group(function () {
  
Route::get('PanelAlmacen',[PanelAlmacenController::class,'index'])->name('PanelAlmacen');
Route::resource('Producto',ProductoController::class)->names('Producto');
Route::resource('Lote',LoteController::class)->names('Lote');
Route::resource('Egresos',EgresosController::class)->names('Egresos');

  
});

<?php

use App\Http\Controllers\MunicipiosController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\PanelTSocialController;
use App\Http\Controllers\ResidenteController;
use App\Http\Controllers\DerivacionController;
use App\Http\Controllers\ReporteResidentesController;
use App\Http\Controllers\EscolaridadController;
use App\Http\Controllers\GestionEscolarController;
use App\Http\Controllers\UnidadEducativaController;



Route::middleware(['auth', 'IsTsocial'])->group(function () {
Route::get('PanelTSocial',[PanelTSocialController::class,'index'])->name('PanelTSocial');
Route::resource('Municipios', MunicipiosController::class)->names('Municipios');
Route::resource('residentes', ResidenteController::class)->names('residentes'); 
Route::resource('derivaciones', DerivacionController::class)
    ->names('derivaciones')
    ->parameters(['derivaciones' => 'derivacion']); // así Laravel usa 'derivacion' como parámetro
Route::get('/reporte/residentes', [ReporteResidentesController::class, 'generarPDF'])->name('reporte.residentes');  
});
Route::resource('escolaridad', EscolaridadController::class)->names('escolaridad');
Route::resource('gestionescolar', GestionEscolarController::class)->names('gestionescolar');  
Route::resource('UEducativa', UnidadEducativaController::class)->names('UEducativa');


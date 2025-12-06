<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ImprimeEstadoResultadosController extends Controller
{
    public function imprimirEstadoDolares()
{
    
    // Recuperar variables desde la sesión
    $fecinicio = session('fecinicio');
    $fecfin = session('fecfin');
    $asientos = session('asientos');
    $cont = session('cont');
    $cuentaux = session('cuentaux');
    $subcuentaux = session('subcuentaux');
    $asientosaux = session('asientosaux');
    $montobs = session('montobs');
    $montosus = session('montosus');
    $sumatotalbs = session('sumatotalbs');
    $sumatotalsus = session('sumatotalsus');
    $sumasubcuentabs = session('sumasubcuentabs');
    $sumasubcuentasus = session('sumasubcuentasus');
    $cuentaidactual = session('cuentaidactual');
    $cuentaidanterior = session('cuentaidanterior');
    $subcuentaidactual = session('subcuentaidactual');
    $subcuentaidanterior = session('subcuentaidanterior');
    $nombrescuentas = session('nombrescuentas');
    $totalmontoscuentasdolares = session('totalmontoscuentasdolares');
    $totalmontoscuentasbolivianos = session('totalmontoscuentasbolivianos');
    $contadorcuentas = session('contadorcuentas');
    $contadortotales = session('contadortotales');
    $anio = session('anio');
    $nombremes = session('nombremes');
    $detallecreditos = session('detallecreditos');
    $interescredito = session('interescredito');
    $sumacreditos = session('sumacreditos');
    $sumadebitos = session('sumadebitos');
    $interesdebito = session('interesdebito');
    $itf = session('itf');
    $resultados = session('resultados');
    $gastosoperativos = session('gastosoperativos');
    $saldo = session('saldo');
    $sumadebitostotales = session('sumadebitostotales');
    $saldofinal = session('saldofinal');

    // Renderizar la vista con todos los datos
    $html = view('Administrador.partials.FormEstadoResultadosDolares', compact(
        'fecinicio',
        'fecfin',
        'asientos',
        'cont',
        'cuentaux',
        'subcuentaux',
        'asientosaux',
        'montobs',
        'montosus',
        'sumatotalbs',
        'sumatotalsus',
        'sumasubcuentabs',
        'sumasubcuentasus',
        'cuentaidactual',
        'cuentaidanterior',
        'subcuentaidactual',
        'subcuentaidanterior',
        'nombrescuentas',
        'totalmontoscuentasdolares',
        'totalmontoscuentasbolivianos',
        'contadorcuentas',
        'contadortotales',
        'anio',
        'nombremes',
        'detallecreditos',
        'interescredito',
        'sumacreditos',
        'sumadebitos',
        'interesdebito',
        'itf',
        'resultados',
        'gastosoperativos',
        'saldo',
        'sumadebitostotales',
        'saldofinal'
    ))->render();

    // Crear nombre de archivo con fecha y hora
    $filename = "{$nombremes}-{$anio}.xls";

    // Devolver respuesta como archivo Excel
    return response($html)
        ->header('Content-Type', 'application/vnd.ms-excel')
        ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
}

public function imprimirEstadoBolivianos(){

     $fecinicio = session('fecinicio');
    $fecfin = session('fecfin');
    $asientos = session('asientos');
    $cont = session('cont');
    $cuentaux = session('cuentaux');
    $subcuentaux = session('subcuentaux');
    $asientosaux = session('asientosaux');
    $montobs = session('montobs');
    $montosus = session('montosus');
    $sumatotalbs = session('sumatotalbs');
    $sumatotalsus = session('sumatotalsus');
    $sumasubcuentabs = session('sumasubcuentabs');
    $sumasubcuentasus = session('sumasubcuentasus');
    $cuentaidactual = session('cuentaidactual');
    $cuentaidanterior = session('cuentaidanterior');
    $subcuentaidactual = session('subcuentaidactual');
    $subcuentaidanterior = session('subcuentaidanterior');
    $nombrescuentas = session('nombrescuentas');
    $totalmontoscuentasdolares = session('totalmontoscuentasdolares');
    $totalmontoscuentasbolivianos = session('totalmontoscuentasbolivianos');
    $contadorcuentas = session('contadorcuentas');
    $contadortotales = session('contadortotales');
    $anio = session('anio');
    $nombremes = session('nombremes');
    $detallecreditos = session('detallecreditos');
    $interescredito = session('interescredito');
    $sumacreditos = session('sumacreditos');
    $sumadebitos = session('sumadebitos');
    $interesdebito = session('interesdebito');
    $itf = session('itf');
    $resultados = session('resultados');
    $gastosoperativos = session('gastosoperativos');
    $saldo = session('saldo');
    $sumadebitostotales = session('sumadebitostotales');
    $saldofinal = session('saldofinal');

    // Renderizar la vista con todos los datos
    $html = view('Administrador.partials.FormEstadoResultadosDolares', compact(
        'fecinicio',
        'fecfin',
        'asientos',
        'cont',
        'cuentaux',
        'subcuentaux',
        'asientosaux',
        'montobs',
        'montosus',
        'sumatotalbs',
        'sumatotalsus',
        'sumasubcuentabs',
        'sumasubcuentasus',
        'cuentaidactual',
        'cuentaidanterior',
        'subcuentaidactual',
        'subcuentaidanterior',
        'nombrescuentas',
        'totalmontoscuentasdolares',
        'totalmontoscuentasbolivianos',
        'contadorcuentas',
        'contadortotales',
        'anio',
        'nombremes',
        'detallecreditos',
        'interescredito',
        'sumacreditos',
        'sumadebitos',
        'interesdebito',
        'itf',
        'resultados',
        'gastosoperativos',
        'saldo',
        'sumadebitostotales',
        'saldofinal'
    ))->render();

    // Crear nombre de archivo con fecha y hora
    $filename = "{$nombremes}-{$anio}.xls";

    // Devolver respuesta como archivo Excel
    return response($html)
        ->header('Content-Type', 'application/vnd.ms-excel')
        ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');

}

}

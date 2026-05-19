<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EstadoResultadosDolaresExport;
use App\Exports\EstadoResultadosBolivianosExport;

class ImprimeEstadoResultadosController extends Controller{
  

public function imprimirEstadoDolares()
{
    $data = [

        'fecinicio' => session('fecinicio'),
        'fecfin' => session('fecfin'),
        'asientos' => session('asientos'),
        'cont' => session('cont'),
        'cuentaux' => session('cuentaux'),
        'subcuentaux' => session('subcuentaux'),
        'asientosaux' => session('asientosaux'),
        'montobs' => session('montobs'),
        'montosus' => session('montosus'),
        'sumatotalbs' => session('sumatotalbs'),
        'sumatotalsus' => session('sumatotalsus'),
        'sumasubcuentabs' => session('sumasubcuentabs'),
        'sumasubcuentasus' => session('sumasubcuentasus'),
        'cuentaidactual' => session('cuentaidactual'),
        'cuentaidanterior' => session('cuentaidanterior'),
        'subcuentaidactual' => session('subcuentaidactual'),
        'subcuentaidanterior' => session('subcuentaidanterior'),
        'nombrescuentas' => session('nombrescuentas'),
        'totalmontoscuentasdolares' => session('totalmontoscuentasdolares'),
        'totalmontoscuentasbolivianos' => session('totalmontoscuentasbolivianos'),
        'contadorcuentas' => session('contadorcuentas'),
        'contadortotales' => session('contadortotales'),
        'anio' => session('anio'),
        'nombremes' => session('nombremes'),
        'detallecreditos' => session('detallecreditos'),
        'interescredito' => session('interescredito'),
        'sumacreditos' => session('sumacreditos'),
        'sumadebitos' => session('sumadebitos'),
        'interesdebito' => session('interesdebito'),
        'itf' => session('itf'),
        'resultados' => session('resultados'),
        'gastosoperativos' => session('gastosoperativos'),
        'saldo' => session('saldo'),
        'sumadebitostotales' => session('sumadebitostotales'),
        'saldofinal' => session('saldofinal'),
    ];

    $filename = 'Estado_Resultados_' .
                session('nombremes') . '_' .
                session('anio') . '.xlsx';

    return Excel::download(
        new EstadoResultadosDolaresExport($data),
        $filename
    );
}

public function imprimirEstadoBolivianos(){
  $data = [

        'fecinicio' => session('fecinicio'),
        'fecfin' => session('fecfin'),
        'asientos' => session('asientos'),
        'cont' => session('cont'),
        'cuentaux' => session('cuentaux'),
        'subcuentaux' => session('subcuentaux'),
        'asientosaux' => session('asientosaux'),
        'montobs' => session('montobs'),
        'sumatotalbs' => session('sumatotalbs'),
        'sumasubcuentabs' => session('sumasubcuentabs'),
        'cuentaidactual' => session('cuentaidactual'),
        'cuentaidanterior' => session('cuentaidanterior'),
        'subcuentaidactual' => session('subcuentaidactual'),
        'subcuentaidanterior' => session('subcuentaidanterior'),
        'nombrescuentas' => session('nombrescuentas'),
        'totalmontoscuentasbolivianos' => session('totalmontoscuentasbolivianos'),
        'contadorcuentas' => session('contadorcuentas'),
        'contadortotales' => session('contadortotales'),
        'anio' => session('anio'),
        'nombremes' => session('nombremes'),
        'detallecreditos' => session('detallecreditos'),
        'interescredito' => session('interescredito'),
        'sumacreditos' => session('sumacreditos'),
        'sumadebitos' => session('sumadebitos'),
        'interesdebito' => session('interesdebito'),
        'resultados' => session('resultados'),
        'gastosoperativos' => session('gastosoperativos'),
        'saldo' => session('saldo'),
        'sumadebitostotales' => session('sumadebitostotales'),
        'saldofinal' => session('saldofinal'),
    ];

    $filename = 'Estado_Resultados_' .
                session('nombremes') . '_' .
                session('anio') . '.xlsx';

    return Excel::download(
        new EstadoResultadosBolivianosExport($data),
        $filename
    );

}

}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MovimientoCuenta;
use Illuminate\Support\Facades\Validator;
use App\Models\Cuenta;
use App\Models\Asiento;
use App\Models\SubCuenta;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReporteMensualController extends Controller
{
    public $sumadebitos, $sumacreditos, $interescredito, $interesdebito, $mes, $anio;

    public function index()
    {

        return view('Administrador.FormReporteMensual');
    }

    public function muestrareportemensual(Request $request)
    {

        $fecha = Carbon::parse($request->fecinicio);
        $nombremes = $fecha->translatedFormat('F');
        $nombremes = strtoupper($nombremes);
        $anio = $fecha->year;
        if ($request->moneda == 'dolares') {

            $totalmontosbs = array();
            $totalmontossus = array();
            $nombrescuentas = array();
            $totalmontoscuentasdolares = array();
            $totalmontoscuentasbolivianos = array();
            $cont = 0;
            $asientosaux = new Asiento();
            $cuentaux = new Cuenta();
            $subcuentaux = new Subcuenta();
            $montobs = 0;
            $montosus = 0;
            $sumatotalbs = 0;
            $sumatotalsus = 0;
            $sumasubcuentabs = 0;
            $sumasubcuentasus = 0;
            $cuentaidactual = 0;
            $cuentaidanterior = 0;
            $subcuentaidactual = 0;
            $subcuentaidanterior = 0;
            $contadorcuentas = 0;
            $contadortotales = 0;
            session(['bandera' => false]);
            session(['inicio' => true]);
            session(['cuentafinal' => false]);


            $asientos1 = DB::table('asientos')
                ->whereBetween('fec_asiento', [$request->fecinicio, $request->fecfin])
                ->orderBy('cuenta', 'ASC')
                ->get();
            $asientos = $asientos1->sortBy('sub_cuenta');
            $detallecreditos = MovimientoCuenta::whereBetween('fecha', [$request->fecinicio, $request->fecfin])
                ->whereNotNull('credito')
                ->where('credito', '>', 0)
                ->where('descripcion', '<>', 'NC PAGO DE INTERESES')
                ->get(['fecha', 'credito']);
            $interescredito = MovimientoCuenta::whereBetween('fecha', [$request->fecinicio, $request->fecfin])
                ->where('descripcion', 'NC PAGO DE INTERESES')
                ->sum('credito');
            $sumacreditos = MovimientoCuenta::whereBetween('fecha', [$request->fecinicio, $request->fecfin])
                ->where('descripcion', 'not like', 'NC PAGO DE INTERESES')
                ->sum('credito');
            $sumadebitos = MovimientoCuenta::whereBetween('fecha', [$request->fecinicio, $request->fecfin])
                ->where('descripcion', 'not like', 'NC PAGO DE INTERESES')
                ->sum('debito');
            $sumadebitostotales = MovimientoCuenta::whereBetween('fecha', [$request->fecinicio, $request->fecfin])
                ->sum('debito');

            $interesdebito = MovimientoCuenta::whereBetween('fecha', [$request->fecinicio, $request->fecfin])
                ->where('descripcion', 'NC PAGO DE INTERESES')
                ->sum('debito');
            -$itf = MovimientoCuenta::whereBetween('fecha', [$request->fecinicio, $request->fecfin])
                ->where('descripcion', 'ND ITF')
                ->sum('debito');


            $primerRegistro = DB::table('movimiento_cuentas')
                ->whereBetween('fecha', [$request->fecinicio, $request->fecfin])
                ->orderBy('fecha', 'asc')
                ->orderBy('id', 'asc') // Agregado para asegurar el orden
                ->first();
            $saldoinicial = $primerRegistro->saldo + $primerRegistro->debito;


            $saldofinal = DB::table('movimiento_cuentas')
                ->latest('fecha')   // Ordena por la columna que indiques
                ->value('saldo');



            $resultados = DB::table('asientos')
                ->join('cuentas', 'asientos.cuenta', '=', 'cuentas.id')
                ->select('cuentas.nombre as cuenta_nombre', DB::raw('SUM(asientos.monto_sus) as total'))
                ->whereBetween('asientos.fec_asiento', [$request->fecinicio, $request->fecfin])
                ->groupBy('asientos.cuenta', 'cuentas.nombre')
                ->get();
            $gastosoperativos = 0;
            foreach ($resultados as $go) {
                $gastosoperativos = $gastosoperativos + $go->total;
            }
            session([
                'fecinicio' => $request->fecinicio,
                'fecfin' => $request->fecfin,
                'asientos' => $asientos,
                'cont' => $cont,
                'cuentaux' => $cuentaux,
                'subcuentaux' => $subcuentaux,
                'asientosaux' => $asientosaux,
                'montobs' => $montobs,
                'montosus' => $montosus,
                'sumatotalbs' => $sumatotalbs,
                'sumatotalsus' => $sumatotalsus,
                'sumasubcuentabs' => $sumasubcuentabs,
                'sumasubcuentasus' => $sumasubcuentasus,
                'cuentaidactual' => $cuentaidactual,
                'cuentaidanterior' => $cuentaidanterior,
                'subcuentaidactual' => $subcuentaidactual,
                'subcuentaidanterior' => $subcuentaidanterior,
                'nombrescuentas' => $nombrescuentas,
                'totalmontoscuentasdolares' => $totalmontoscuentasdolares,
                'totalmontoscuentasbolivianos' => $totalmontoscuentasbolivianos,
                'contadorcuentas' => $contadorcuentas,
                'contadortotales' => $contadortotales,
                'anio' => $anio,
                'nombremes' => $nombremes,
                'detallecreditos' => $detallecreditos,
                'interescredito' => $interescredito,
                'sumacreditos' => $sumacreditos,
                'sumadebitos' => $sumadebitos,
                'interesdebito' => $interesdebito,
                'itf' => $itf,
                'resultados' => $resultados,
                'gastosoperativos' => $gastosoperativos,
                'saldo' => $saldoinicial,
                'sumadebitostotales' => $sumadebitostotales,
                'saldofinal' => $saldofinal,
            ]);

            $cont = 0;
            return view('Administrador.FormEstadoResultadosDolares')->with(['fecinicio' => $request->fecinicio, 'fecfin' => $request->fecfin, 'asientos' => $asientos, 'cont' => $cont, 'cuentaux' => $cuentaux, 'subcuentaux' => $subcuentaux, 'asientosaux' => $asientosaux, 'cont' => $cont, 'montobs' => $montobs, 'montosus' => $montosus, 'sumatotalbs' => $sumatotalbs, 'sumatotalsus' => $sumatotalsus, 'sumasubcuentabs' => $sumasubcuentabs, 'sumasubcuentasus' => $sumasubcuentasus, 'cuentaidactual' => $cuentaidactual, 'cuentaidanterior' => $cuentaidanterior, 'subcuentaidactual' => $subcuentaidactual, 'subcuentaidanterior' => $subcuentaidanterior, 'nombrescuentas' => $nombrescuentas, 'totalmontoscuentasdolares' => $totalmontoscuentasdolares, 'totalmontoscuentasbolivianos' => $totalmontoscuentasbolivianos, 'contadorcuentas' => $contadorcuentas, 'contadortotales' => $contadortotales, 'anio' => $anio, 'nombremes' => $nombremes, 'fecinicio' => $request->fecinicio, 'fecfin' => $request->fecfin, 'detallecreditos' => $detallecreditos, 'interescredito' => $interescredito, 'sumacreditos' => $sumacreditos, 'sumadebitos' => $sumadebitos, 'interesdebito' => $interesdebito, 'itf' => $itf, 'resultados' => $resultados, 'gastosoperativos' => $gastosoperativos, 'saldo' => $saldoinicial, 'sumadebitostotales' => $sumadebitostotales, 'saldofinal' => $saldofinal]);


            /*---------------------------aqui termina el cuadro general -----------*/

            /************Aqui empieza la obtención de datos para mostrar el estado de resultados******/

            $debitobatch = MovimientoCuenta::whereBetween('fecha', [$request->fecinicio, $request->fecfin])
                ->where('descripcion', 'NOTA DEBITO CARGOS BATCH')
                ->sum('debito');
        } else {


            $fecha = Carbon::parse($request->fecinicio);
            $nombremes = $fecha->translatedFormat('F');
            $nombremes = strtoupper($nombremes);
            $anio = $fecha->year;


            $totalmontosbs = array();
            $totalmontossus = array();
            $nombrescuentas = array();
            $totalmontoscuentasdolares = array();
            $totalmontoscuentasbolivianos = array();
            $cont = 0;
            $asientosaux = new Asiento();
            $cuentaux = new Cuenta();
            $subcuentaux = new Subcuenta();
            $montobs = 0;
            $montosus = 0;
            $sumatotalbs = 0;
            $sumatotalsus = 0;
            $sumasubcuentabs = 0;
            $sumasubcuentasus = 0;
            $cuentaidactual = 0;
            $cuentaidanterior = 0;
            $subcuentaidactual = 0;
            $subcuentaidanterior = 0;
            $contadorcuentas = 0;
            $contadortotales = 0;
            session(['bandera' => false]);
            session(['inicio' => true]);
            session(['cuentafinal' => false]);


            $asientos1 = DB::table('asientos')
                ->whereBetween('fec_asiento', [$request->fecinicio, $request->fecfin])
                ->orderBy('cuenta', 'ASC')
                ->get();
            $asientos = $asientos1->sortBy('sub_cuenta');
            $detallecreditos = MovimientoCuenta::whereBetween('fecha', [$request->fecinicio, $request->fecfin])
                ->whereNotNull('credito')
                ->where('credito', '>', 0)
                ->where('descripcion', '<>', 'NC PAGO DE INTERESES')
                ->get(['fecha', 'credito']);
            $interescredito = MovimientoCuenta::whereBetween('fecha', [$request->fecinicio, $request->fecfin])
                ->where('descripcion', 'NC PAGO DE INTERESES')
                ->sum('credito');
            $sumacreditos = MovimientoCuenta::whereBetween('fecha', [$request->fecinicio, $request->fecfin])
                ->where('descripcion', 'not like', 'NC PAGO DE INTERESES')
                ->sum('credito');
            $sumadebitos = MovimientoCuenta::whereBetween('fecha', [$request->fecinicio, $request->fecfin])
                ->where('descripcion', 'not like', 'NC PAGO DE INTERESES')
                ->sum('debito');
            $sumadebitostotales = MovimientoCuenta::whereBetween('fecha', [$request->fecinicio, $request->fecfin])
                ->sum('debito');

            $interesdebito = MovimientoCuenta::whereBetween('fecha', [$request->fecinicio, $request->fecfin])
                ->where('descripcion', 'NC PAGO DE INTERESES')
                ->sum('debito');


            $primerRegistro = DB::table('movimiento_cuentas')
                ->whereBetween('fecha', [$request->fecinicio, $request->fecfin])
                ->orderBy('fecha', 'asc')
                ->orderBy('id', 'asc') // Agregado para asegurar el orden
                ->first();
            $saldoinicial = $primerRegistro->saldo + $primerRegistro->debito;


            $saldofinal = DB::table('movimiento_cuentas')
                ->latest('fecha')   // Ordena por la columna que indiques
                ->value('saldo');



            $resultados = DB::table('asientos')
                ->join('cuentas', 'asientos.cuenta', '=', 'cuentas.id')
                ->select('cuentas.nombre as cuenta_nombre', DB::raw('SUM(asientos.monto_bs) as total'))
                ->whereBetween('asientos.fec_asiento', [$request->fecinicio, $request->fecfin])
                ->groupBy('asientos.cuenta', 'cuentas.nombre')
                ->get();
            $gastosoperativos = 0;
            foreach ($resultados as $go) {
                $gastosoperativos = $gastosoperativos + $go->total;
            }
            session([
                'fecinicio' => $request->fecinicio,
                'fecfin' => $request->fecfin,
                'asientos' => $asientos,
                'cont' => $cont,
                'cuentaux' => $cuentaux,
                'subcuentaux' => $subcuentaux,
                'asientosaux' => $asientosaux,
                'montobs' => $montobs,
                'montosus' => $montosus,
                'sumatotalbs' => $sumatotalbs,
                'sumatotalsus' => $sumatotalsus,
                'sumasubcuentabs' => $sumasubcuentabs,
                'sumasubcuentasus' => $sumasubcuentasus,
                'cuentaidactual' => $cuentaidactual,
                'cuentaidanterior' => $cuentaidanterior,
                'subcuentaidactual' => $subcuentaidactual,
                'subcuentaidanterior' => $subcuentaidanterior,
                'nombrescuentas' => $nombrescuentas,
                'totalmontoscuentasdolares' => $totalmontoscuentasdolares,
                'totalmontoscuentasbolivianos' => $totalmontoscuentasbolivianos,
                'contadorcuentas' => $contadorcuentas,
                'contadortotales' => $contadortotales,
                'anio' => $anio,
                'nombremes' => $nombremes,
                'detallecreditos' => $detallecreditos,
                'interescredito' => $interescredito,
                'sumacreditos' => $sumacreditos,
                'sumadebitos' => $sumadebitos,
                'interesdebito' => $interesdebito,
                'resultados' => $resultados,
                'gastosoperativos' => $gastosoperativos,
                'saldo' => $saldoinicial,
                'sumadebitostotales' => $sumadebitostotales,
                'saldofinal' => $saldofinal,
            ]);

            $cont = 0;
            return view('Administrador.FormEstadoResultadosBolivianos')->with(['fecinicio' => $request->fecinicio, 'fecfin' => $request->fecfin, 'asientos' => $asientos, 'cont' => $cont, 'cuentaux' => $cuentaux, 'subcuentaux' => $subcuentaux, 'asientosaux' => $asientosaux, 'cont' => $cont, 'montobs' => $montobs, 'montosus' => $montosus, 'sumatotalbs' => $sumatotalbs, 'sumatotalsus' => $sumatotalsus, 'sumasubcuentabs' => $sumasubcuentabs, 'sumasubcuentasus' => $sumasubcuentasus, 'cuentaidactual' => $cuentaidactual, 'cuentaidanterior' => $cuentaidanterior, 'subcuentaidactual' => $subcuentaidactual, 'subcuentaidanterior' => $subcuentaidanterior, 'nombrescuentas' => $nombrescuentas, 'totalmontoscuentasdolares' => $totalmontoscuentasdolares, 'totalmontoscuentasbolivianos' => $totalmontoscuentasbolivianos, 'contadorcuentas' => $contadorcuentas, 'contadortotales' => $contadortotales, 'anio' => $anio, 'nombremes' => $nombremes, 'fecinicio' => $request->fecinicio, 'fecfin' => $request->fecfin, 'detallecreditos' => $detallecreditos, 'interescredito' => $interescredito, 'sumacreditos' => $sumacreditos, 'sumadebitos' => $sumadebitos, 'interesdebito' => $interesdebito, 'resultados' => $resultados, 'gastosoperativos' => $gastosoperativos, 'saldo' => $saldoinicial, 'sumadebitostotales' => $sumadebitostotales, 'saldofinal' => $saldofinal]);
        } //fin if-else

    } //fin funcion
}

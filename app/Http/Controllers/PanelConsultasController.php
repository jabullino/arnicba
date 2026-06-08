<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cuenta;
use App\Models\Asiento;
use App\Models\SubCuenta;
use Illuminate\Support\Facades\DB;


class PanelConsultasController extends Controller
{

    public function getPanelConsutasContabilidad()
    {
        $cuentas = Cuenta::all();
        return view('Administrador.PanelConsultasContabilidad')->with(['cuentas' => $cuentas]);
    }

    public function verificaOpcion(Request $request)
    {
     

        if ($request->has('consultacuentaysubcuenta')) {
            $request->validate(
                [
                    'fecinicio' => 'required|date|before_or_equal:today|before_or_equal:fecfin',
                    'fecfin' => 'required|date|before_or_equal:today|after_or_equal:fecinicio',
                    'consultas' => 'required',
                    'cuenta' => 'required',
                    'subcuenta' => 'required',

                ],
                [
                    'fecinicio.required' => 'El campo Fecha Inicio es obligatorio.',
                    'fecfin.required' => 'El campo fecha fin es obligarorioa',
                    'consultas.required' => 'Debe escoger una opcion de consultas',

                ]

            );
        } elseif($request->has('particularcuenta')) {

            $request->validate(
                [
                    'fecinicio' => 'required|date|before_or_equal:today|before_or_equal:fecfin',
                    'fecfin' => 'required|date|before_or_equal:today|after_or_equal:fecinicio',
                    'consultas' => 'required',

                ],
                [
                    'fecinicio.required' => 'El campo Fecha Inicio es obligatorio.',
                    'fecfin.required' => 'El campo fecha fin es obligarorioa',
                    'consultas.required' => 'Debe escoger una opcion de consultas',

                ]


            );
        }else{
                
            $request->validate(
                [
                    'fecinicio' => 'required|date|before_or_equal:today|before_or_equal:fecfin',
                    'fecfin' => 'required|date|before_or_equal:today|after_or_equal:fecinicio',
                   

                ],
                [
                    'fecinicio.required' => 'El campo Fecha Inicio es obligatorio.',
                    'fecfin.required' => 'El campo fecha fin es obligarorioa',
                   

                ]


            );


        }
        
        $totalmontosbs = array();
        $totalmontossus = array();
        if ($request->consultas == 'generalcuenta') {
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
            session(['bandera' => false]);
            session(['inicio' => true]);
            session(['cuentafinal' => false]);


        /*   $asientos1 = Asiento::whereBetween( 'fec_asiento', [$request->fecinicio, $request->fecfin])
                        ->orderBy('cuenta', 'ASC')
                        ->get();*/
           $asientos1 = Asiento::whereBetween('fec_asiento', [
                    $request->fecinicio,
                    $request->fecfin
                ])
                ->orderBy('cuenta', 'ASC')
                 ->orderBy('fec_asiento', 'ASC')
                ->orderBy('id','ASC')               
                ->get();

           $asientos = $asientos1->sortBy('sub_cuenta');


            /*$asientos=Asiento::groupBy('cuenta')->whereBetween('fec_asiento', [$request->fecinicio,$request->fecfin ]);*/
            $cont = 0;
            return view('Administrador.FormConsultaGeneralCuentaSubCuentaPeriodo')->with(['fecinicio' => $request->fecinicio, 'fecfin' => $request->fecfin, 'asientos' => $asientos, 'cont' => $cont, 'cuentaux' => $cuentaux, 'subcuentaux' => $subcuentaux, 'asientosaux' => $asientosaux, 'cont' => $cont, 'montobs' => $montobs, 'montosus' => $montosus, 'sumatotalbs' => $sumatotalbs, 'sumatotalsus' => $sumatotalsus, 'sumasubcuentabs' => $sumasubcuentabs, 'sumasubcuentasus' => $sumasubcuentasus, 'cuentaidactual' => $cuentaidactual, 'cuentaidanterior' => $cuentaidanterior, 'subcuentaidactual' => $subcuentaidactual, 'subcuentaidanterior' => $subcuentaidanterior]);
        } elseif ($request->consultas == 'particularcuenta') {
            $asientos1 = Asiento::whereBetween('fec_asiento', [$request->fecinicio, $request->fecfin])
                ->where('cuenta', $request->cuenta)
                ->where('estado_id', 1)               
                ->orderBy('fec_asiento','ASC')
                ->orderBy('id','ASC') 
                ->get();
            $asientos = $asientos1->sortBy('sub_cuenta');

            $cont = 0;
            $asientosaux = new Asiento();
            $montobs = 0;
            $montosus = 0;
            $subcuentaidactual = 0;
            $subcuentaidanterior = 0;
            session(['bandera' => false]);
            session(['inicio' => true]);
            $sumasubcuentabs = 0;
            $sumasubcuentasus = 0;
            $nomcuenta = new Cuenta();
            $nombrecuenta = strtoupper($nomcuenta->getCuenta($request->cuenta));
            $subcuentaux = new Subcuenta();
            $nombresubcuenta = '';
            return view('Administrador.FormConsultaCuentaPeriodo')->with(['fecinicio' => $request->fecinicio, 'fecfin' => $request->fecfin, 'asientos' => $asientos, 'cont' => $cont, 'asientosaux' => $asientosaux, 'montobs' => $montobs, 'montosus' => $montosus, 'nombrecuenta' => $nombrecuenta, 'sumasubcuentabs' => $sumasubcuentabs, 'sumasubcuentasus' => $sumasubcuentasus, 'subcuentaidanterior' => $subcuentaidanterior, 'subcuentaidactual' => $subcuentaidactual, 'subcuentaux' => $subcuentaux, 'nombresubcuenta' => $nombresubcuenta]);
        } else {
            $asientos = Asiento::whereBetween('fec_asiento', [$request->fecinicio, $request->fecfin])
                ->where('cuenta', $request->cuenta)
                ->where('sub_cuenta', $request->subcuenta)
                ->where('estado_id', 1)
                ->orderBy('fec_asiento','ASC')
                ->orderBy('id','ASC')
                ->get();
            $cont = 0;
            $asientosaux = new Asiento();
            $montobs = 0;
            $montosus = 0;
            $sumasubcuentabs = 0;
            $sumasubcuentasus = 0;
            $nomcuenta = new Cuenta();
            $nomsubcuenta = new SubCuenta();
            $nombrecuenta = strtoupper($nomcuenta->getCuenta($request->cuenta));
            $nombresubcuenta = strtoupper($nomsubcuenta->getSubcuenta($request->subcuenta));
            $subcuentaux = new Subcuenta();
            return view('Administrador.FormConsultaCuentaSubCuentaPeriodo')->with(['fecinicio' => $request->fecinicio, 'fecfin' => $request->fecfin, 'asientos' => $asientos, 'cont' => $cont, 'asientosaux' => $asientosaux, 'montobs' => $montobs, 'montosus' => $montosus, 'nombrecuenta' => $nombrecuenta, 'subcuentaux' => $subcuentaux, 'nombresubcuenta' => $nombresubcuenta]);
        }
    }

    public function getConsultaGeneralCuentaSubcuenta($fecinicio, $fecfin)
    {
        $cuentasaux = new Cuenta();
        $subcuentasaux = new SubCuenta();
        $asientosaux = new Asiento();
        $cont = 0;
        $nomcuentas = Cuenta::pluck('nombre');
        $nomsubcuentas = SubCuenta::pluck('nombre');
        $asientos = Asiento::whereBetween('fec_asiento', [$fecinicio, $fecfin])->orderBy('fec_asiento', 'ASC')->groupBy('cuenta');
        return view('Administrador.FormConsultaGeneralPeriodo')->with(['asientos' => $asientos, 'cuentasaux' => $cuentasaux, 'subcuentasaux' => $subcuentasaux, 'cont' => $cont, 'nomcuentas' => $nomcuentas, 'nomsubcuentas', $nomsubcuentas]);
    }

    public function getConsultaParticularCuentaSubcuenta($fecinicio, $fecfin)
    {
        $cuentasaux = new Cuenta();
        $subcuentasaux = new SubCuenta();
        $asientosaux = new Asiento();
        $cont = 0;
        $nomcuentas = Cuenta::pluck('nombre');
        $nomsubcuentas = SubCuenta::pluck('nombre');
        $asientos = Asiento::whereBetween('fec_asiento', [$fecinicio, $fecfin])->orderBy('fec_asiento', 'ASC')->groupBy('cuenta');
        return view('Administrador.FormConsultaParticularPeriodo')->with(['asientos' => $asientos, 'cuentasaux' => $cuentasaux, 'subcuentasaux' => $subcuentasaux, 'cont' => $cont, 'nomcuentas' => $nomcuentas, 'nomsubcuentas', $nomsubcuentas]);
    }
}

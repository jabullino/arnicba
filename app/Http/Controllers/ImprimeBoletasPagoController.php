<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gestion;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;


class ImprimeBoletasPagoController extends Controller
{
    public function index()
    {

        $gestiones = Gestion::all();

        return view('Administrador.FormFechaBoletasPago')->with(['gestiones' => $gestiones]);
    }

  public function extraeBoletas(Request $request)
{
    $gestion_id = $request->gestion;
    $mesId = $this->devuelveMes($request->mes);
    $gestion_nombre = Gestion::where('id', $gestion_id)->value('nombre');
    $mes = $request->input('mes');
     
    // 1️⃣ Usuarios con haber básico
    $usuarios = DB::table('users')
    ->join('cargos', 'users.cargo_id', '=', 'cargos.id')
    ->leftJoin('extensiones', 'users.extension_id', '=', 'extensiones.id')
    ->leftJoin('haber_basicos', function ($join) {
        $join->on('cargos.id', '=', 'haber_basicos.cargo_id')
             ->whereRaw('haber_basicos.id = (
                 SELECT MAX(hb.id)
                 FROM haber_basicos hb
                 WHERE hb.cargo_id = cargos.id
             )');
    })
    ->whereNull('users.deleted_at')
    ->where('users.id', '!=', 1)
    ->select(
        'users.id as user_id',
        'users.nombre',
        'users.apellido',
        'users.ci',
        'users.fec_ingreso',
        'cargos.nombre as cargo',
        'extensiones.nombre as extension',
        'haber_basicos.monto as haber_basico'
    )
    ->get()
    ->keyBy('user_id');


    $userIds = $usuarios->keys();

    // 2️⃣ Bonos (LEFT JOIN para no perder nada)
    $bonos = DB::table('sueldos')
        ->leftJoin('bono_sueldo', 'sueldos.id', '=', 'bono_sueldo.sueldo_id')
        ->leftJoin('bonos', 'bono_sueldo.bono_id', '=', 'bonos.id')
        ->whereIn('sueldos.user_id', $userIds)
        ->where('sueldos.gestion_id', $gestion_id)
        ->where('sueldos.mes', $mesId)
        ->select('sueldos.user_id', 'bonos.nombre', 'bono_sueldo.monto')
        ->get()
        ->groupBy('user_id');

    // 3️⃣ Descuentos (también con LEFT JOIN)
    $descuentos = DB::table('sueldos')
        ->leftJoin('descuento_sueldo', 'sueldos.id', '=', 'descuento_sueldo.sueldo_id')
        ->leftJoin('descuentos', 'descuento_sueldo.descuento_id', '=', 'descuentos.id')
        ->whereIn('sueldos.user_id', $userIds)
        ->where('sueldos.gestion_id', $gestion_id)
        ->where('sueldos.mes', $mesId)
        ->select('sueldos.user_id', 'descuentos.nombre', 'descuento_sueldo.monto')
        ->get()
        ->groupBy('user_id');
$administrador = DB::table('users')
            ->join('cargos', 'users.cargo_id', '=', 'cargos.id')
            ->join('extensiones', 'users.extension_id', '=', 'extensiones.id')
            ->select(
                'users.nombre',
                'users.apellido',
                'users.ci',
                'cargos.nombre as cargo',
                'extensiones.nombre as extension'
            )
            ->where('users.cargo_id', 4)
            ->first(); // devuelve un solo registro
    return view('Administrador.FormImprimeBoletasPago', [
        'usuarios'   => $usuarios,
        'bonos'      => $bonos,
        'descuentos' => $descuentos,
        'gestion'    => $gestion_nombre,
        'mes'        => $mes,
        'administrador'=>$administrador,
    ]);
    
}

    public function devuelveMes($mes)
    {

        if ($mes == 'ENERO') {
            return 1;
        } elseif ($mes == 'FEBRERO') {
            return 2;
        } elseif ($mes == 'MARZO') {
            return 3;
        } elseif ($mes == 'ABRIL') {
            return 4;
        } elseif ($mes == 'MAYO') {
            return 5;
        } elseif ($mes == 'JUNIO') {
            return 6;
        } elseif ($mes == 'JULIO') {
            return 7;
        } elseif ($mes == 'AGOSTO') {
            return 8;
        } elseif ($mes == 'SEPTIEMBRE') {
            return 9;
        } elseif ($mes == 'OCTUBRE') {
            return 10;
        } elseif ($mes == 'NOVIEMBRE') {
            return 11;
        } else {
            return 12;
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gestion;
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
    $mesId = $this->devuelveMesId($request->mes);
    $gestion_nombre = Gestion::where('id', $gestion_id)->value('nombre');
    $mes = $request->input('mes');
     
    // 1️⃣ Usuarios con haber básico + tipo_id
    $usuarios = DB::table('users')
    ->join('cargos', 'users.cargo_id', '=', 'cargos.id')
    ->leftJoin('extensiones', 'users.extension_id', '=', 'extensiones.id')

    // 🔹 NUEVO JOIN A PERSONAL
    ->leftJoin('personal', 'users.id', '=', 'personal.user_id')

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
        'haber_basicos.monto as haber_basico',

        // 🔹 NUEVO CAMPO
        'personal.tipo_id'
    )
    ->get()
    ->keyBy('user_id');


    $userIds = $usuarios->keys();
    
    // 2️⃣ Bonos
    $bonos = DB::table('sueldos')
        ->leftJoin('bono_sueldo', 'sueldos.id', '=', 'bono_sueldo.sueldo_id')
        ->leftJoin('bonos', 'bono_sueldo.bono_id', '=', 'bonos.id')
        ->whereIn('sueldos.user_id', $userIds)
        ->where('sueldos.gestion_id', $gestion_id)
        ->where('sueldos.mes', $mesId)
        ->select('sueldos.user_id', 'bonos.nombre', 'bono_sueldo.monto')
        ->get()
        ->groupBy('user_id');

    // 3️⃣ Descuentos
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
        ->first();
$mesNumero = (int) $request->mes;

if ($mesNumero < 1 || $mesNumero > 12) {
    $mesNumero = 1;
}
$mesTexto = $this->mesAnteriorNombre($mesNumero);
    return view('Administrador.FormImprimeBoletasPago', [
        'usuarios'   => $usuarios,
        'bonos'      => $bonos,
        'descuentos' => $descuentos,
        'gestion'    => $gestion_nombre,
        'mes'        => $mes,
        'mesTexto'   => $mesTexto,
        'administrador' => $administrador,
    ]);
}
    // 🔹 Nuevo método para devolver nombre del mes
    public function nombreMes($numero)
    {
        $meses = [
            1 => 'ENERO',
            2 => 'FEBRERO',
            3 => 'MARZO',
            4 => 'ABRIL',
            5 => 'MAYO',
            6 => 'JUNIO',
            7 => 'JULIO',
            8 => 'AGOSTO',
            9 => 'SEPTIEMBRE',
            10 => 'OCTUBRE',
            11 => 'NOVIEMBRE',
            12 => 'DICIEMBRE',
        ];

        return $meses[$numero] ?? 'ENERO';
    }

public function devuelveMesId($numero)
{
    $meses = [
        1 => 1,
        2 => 2,
        3 => 3,
        4 => 4,
        5 => 5,
        6 => 6,
        7 => 7,
        8 => 8,
        9 => 9,
        10 => 10,
        11 => 11,
        12 => 12,
    ];

    return $meses[$numero] ?? 1;
}

public function mesAnteriorNombre($mesNumero)
{
    $mesNumero = (int) $mesNumero;

    $meses = [
        1 => 'ENERO',
        2 => 'FEBRERO',
        3 => 'MARZO',
        4 => 'ABRIL',
        5 => 'MAYO',
        6 => 'JUNIO',
        7 => 'JULIO',
        8 => 'AGOSTO',
        9 => 'SEPTIEMBRE',
        10 => 'OCTUBRE',
        11 => 'NOVIEMBRE',
        12 => 'DICIEMBRE',
    ];

    // 🔥 VALIDACIÓN CLAVE
    if ($mesNumero < 1 || $mesNumero > 12) {
        return 'ENERO'; // valor por defecto seguro
    }

    // Enero → Diciembre
    if ($mesNumero == 1) {
        return $meses[12];
    }

    return $meses[$mesNumero - 1];
}
}

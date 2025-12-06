<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EntregasCajaChica;
use App\Models\Gestion;
use App\Models\CajaChica;

class AdministradorEntregasController extends Controller
{
   public function index(Request $request)
    {
        // Todas las gestiones disponibles para los selects
        $gestiones = Gestion::select('id', 'nombre')->orderByDesc('nombre')->get();

        // Array de meses para los selects
        $meses = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre'
        ];

        // Entregas no se envían porque se cargarán por AJAX
        return view('Administrador.EntregasCajaChica.index', compact('gestiones', 'meses'));
    }

    public function filtrar(Request $request)
    {
       /* $anio = $request->get('anio');
        $mes = $request->get('mes');

        $query = EntregasCajaChica::with(['cajachica.gestion']);

        if ($anio) {
            $query->whereHas('cajachica.gestion', function ($q) use ($anio) {
                $q->where('id', $anio);
            });
        }

        if ($mes) {
            $query->where('mes', $mes);
        }

        $entregas = $query->get();

        return response()->json($entregas);*/
    }
    public function ajaxTotales(Request $request)
{
    $anio = $request->get('anio');
    $mes  = $request->get('mes');

    $query = EntregasCajaChica::with('cajachica');

    if ($anio) {
        $query->whereHas('cajachica.gestion', function ($q) use ($anio) {
            $q->where('nombre', $anio);
        });
    }

    if ($mes) {
        $query->where('mes', $mes);
    }

    $entregas = $query->get();
    $total = $entregas->sum('monto'); // 👈 suma total de montos

    return response()->json([
        'entregas' => $entregas,
        'total' => $total
    ]);
}
}

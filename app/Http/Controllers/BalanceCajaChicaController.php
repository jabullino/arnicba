<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BalanceCajaChicaController extends Controller
{
    public function resumen(Request $request)
    {
        $gestiones = DB::table('gestiones')->get();

        $gestionId = $request->gestion_id;
        $mes = $request->mes;

        $ingresos = collect();
        $egresos = collect();
        $totalIngresos = 0;
        $totalEgresos = 0;
        $diferencia = 0;
        $mensaje = '';
        $color = 'bg-secondary';

        if ($gestionId && $mes) {

            $gestion = DB::table('gestiones')->where('id', $gestionId)->first();
            $year = $gestion->nombre;

            $inicio = Carbon::createFromDate($year, $mes, 1)->startOfMonth();
            $fin = Carbon::createFromDate($year, $mes, 1)->endOfMonth();

            $ingresos = DB::table('entregas_caja_chicas')
    ->select('monto', 'fecha_entrega as fecha')
    ->whereBetween('fecha_entrega', [$inicio, $fin])
    ->get();

$egresos = DB::table('gastos_caja_chicas')
    ->select('importe as monto', 'fecha_doc as fecha') // 🔥 clave
    ->whereBetween('fecha_doc', [$inicio, $fin])       // 🔥 NO uses "registro"
    ->get();

$totalIngresos = $ingresos->sum('monto');
$totalEgresos = $egresos->sum('monto'); // 🔥 ahora funciona porque hicimos alias

            $diferencia = $totalIngresos - $totalEgresos;

            if ($diferencia > 0) {
                $mensaje = 'Existe sobrante';
                $color = 'bg-secondary';
            } elseif ($diferencia < 0) {
                $mensaje = 'Existe faltante';
                $color = 'bg-danger';
            } else {
                $mensaje = 'Balance correcto';
                $color = 'bg-success';
            }
        }

        return view('Administrador.CajaChica.resumencajachica', compact(
            'gestiones',
            'ingresos',
            'egresos',
            'totalIngresos',
            'totalEgresos',
            'diferencia',
            'mensaje',
            'color',
            'gestionId',
            'mes'
        ));
    }
}



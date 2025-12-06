<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlumnosController extends Controller
{
    public function alumnos(Request $request)
{
    $gestionId = $request->get('gestion_id');

    try {
        $data = DB::table('grado_residente_unidad_educativa as gru')
            ->leftJoin('residentes as r', 'gru.residente_id', '=', 'r.id')
            ->leftJoin('unidad_educativas as ue', 'gru.ue_id', '=', 'ue.id')
            ->leftJoin('grados as g', 'gru.grado_id', '=', 'g.id')
            ->leftJoin('cursos as c', 'gru.curso_id', '=', 'c.id')
            ->when($gestionId, fn($q) => $q->where('gru.gestion_id', $gestionId))
            ->select(
                'gru.id',
                'r.nombre',
                'r.apellido',
                'ue.nombre as unidad_educativa',
                'g.nombre as grado',
                'c.nombre as curso'
            )
            ->get();

        return response()->json(['data' => $data]);
    } catch (\Exception $e) {
        return response()->json(['data' => [], 'error' => $e->getMessage()], 500);
    }
}

}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gestion;
use App\Models\Residente;
Use App\Models\UnidadEducativa;
use App\Models\Curso;
use App\Models\Grado;
use Illuminate\Support\Facades\DB;
use Exception;

class EscolaridadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index(Request $request)
{
    if ($request->ajax() || $request->has('gestion_id')) {
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
                    'gru.rude',
                    'r.nombre',
                    'r.apellido',
                    'ue.nombre as unidad_educativa',
                    'g.nombre as grado',
                    'c.nombre as curso'
                )
                ->get();

            return response()->json(['data' => $data]);
        } catch (\Exception $e) {
            return response()->json([
                'data' => [],
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Vista normal
    $gestiones = DB::table('gestiones')->get();
    return view('TSocial.Escolaridad.ListaAlumnos', compact('gestiones'));
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $gestiones=Gestion::all();
        $residentes = Residente::whereNotIn('id', function ($query) {
            $query->select('residente_id')
                  ->from('grado_residente_unidad_educativa');
        })->get();
        $ueducativas=UnidadEducativa::all();
        $cursos=Curso::all();
        $grados=Grado::all();
        return view('TSocial.Escolaridad.CreaRegistroEscolar',compact('gestiones','residentes','ueducativas','cursos','grados'));
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
{
    // 🔹 Validación: todos los campos requeridos
    $validated = $request->validate([
        'residente' => 'required',
        'gestion' => 'required',
        'ueducativa' => 'required',
        'grado' => 'required',
        'curso' => 'required',
        'rude' => 'nullable|string|max:70|regex:/^[0-9]+$/',
    ]);

    DB::beginTransaction();
    try {
        // 🔹 Verificar si ya existe un registro para este residente y gestión
        $existe = DB::table('grado_residente_unidad_educativa')
            ->where('residente_id', $request->residente)
            ->where('gestion_id', $request->gestion)
            ->exists();

        if ($existe) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Ya existe un registro para este residente en la gestión seleccionada.');
        }

        // 🔹 Insertar en la tabla pivot
        DB::table('grado_residente_unidad_educativa')->insert([
            'residente_id' => $request->residente,
            'gestion_id'   => $request->gestion,
            'ue_id'        => $request->ueducativa,
            'grado_id'     => $request->grado,
            'curso_id'     => $request->curso,
            'rude'         => $request->rude, 
        ]);

        DB::commit();

        // 🔹 Redirigir con mensaje de éxito
        return redirect()->back()->with('success', 'Datos registrados correctamente.');

    } catch (Exception $e) {
        DB::rollBack();

        // 🔹 Redirigir con mensaje de error
        return redirect()->back()->with('error', 'Ocurrió un error al registrar los datos. Inténtalo nuevamente.');
    }
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
{
    // Registro a editar
    $registro = DB::table('grado_residente_unidad_educativa')->where('id', $id)->first();
    
    if (!$registro) {
        abort(404, 'Registro no encontrado');
    }

    // Alumno relacionado
    $residente = DB::table('residentes')->where('id', $registro->residente_id)->first();

    // Opciones para los select
    $unidades = DB::table('unidad_educativas')->get();
    $cursos   = DB::table('cursos')->get();
    $grados   = DB::table('grados')->get();

    return view('TSocial.Escolaridad.EditaRegistroEscolar', compact('registro', 'residente', 'unidades', 'cursos', 'grados'));
}


    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, $id)
{
    $request->validate([
        'ue_id' => 'required|exists:unidad_educativas,id',
        'curso_id' => 'required|exists:cursos,id',
        'grado_id' => 'required|exists:grados,id',
        'rude' => 'nullable|string|max:30|regex:/^[0-9]+$/',
    ]);

    $registro = DB::table('grado_residente_unidad_educativa')
        ->where('id', $id)
        ->first();

    if (!$registro) {
        return response()->json([
            'success' => false,
            'message' => 'Registro no encontrado'
        ]);
    }

    $actualizado = DB::table('grado_residente_unidad_educativa')
        ->where('id', $id)
        ->update([
            'ue_id' => $request->ue_id,
            'curso_id' => $request->curso_id,
            'grado_id' => $request->grado_id,
            'rude'     => $request->rude,
            'updated_at' => now()
        ]);

    if ($actualizado) {
        return response()->json(['success' => true]);
    }

    return response()->json([
        'success' => false,
        'message' => 'No se pudo actualizar el registro'
    ]);
}


    /**
     * Remove the specified resource from storage.
     */
   public function destroy($id)
    {
        $eliminado = DB::table('grado_residente_unidad_educativa')
            ->where('id', $id)
            ->delete();

        if ($eliminado) {
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'No se pudo eliminar']);
    }
}

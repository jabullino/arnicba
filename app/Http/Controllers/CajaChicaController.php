<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CajaChica;
use App\Models\Gestion;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class CajaChicaController extends Controller
{
    /**
     * Mostrar listado de Cajas Chicas activas
     */
    public function index()
    {
        // Traer la gestión activa (solo una)
        $gestionActiva = Gestion::whereNull('deleted_at')->first();

        // Traer cajas chicas solo de la gestión activa
        $cajachicas = [];
        if ($gestionActiva) {
            $cajachicas = CajaChica::where('gestion_id', $gestionActiva->id)
                ->with('gestion')
                ->get();
        }
           
        return view('AdminSis.cajachicas.index', compact('cajachicas', 'gestionActiva'));
    }




    /**
     * Formulario para crear Caja Chica
     */
    public function create()
    {
        $gestionesActivas = Gestion::whereNull('deleted_at')->get();
        return view('AdminSis.cajachicas.create', compact('gestionesActivas'));
    }

    /**
     * Guardar nueva Caja Chica
     */
    public function store(Request $request)
    {
       
        $request->validate([
            'gestion_id' => 'required|exists:gestiones,id',
        ]);
        
        try {
            DB::beginTransaction();
            CajaChica::create([
                'gestion_id' => $request->gestion_id,
            ]);
             session()->flash('success', '¡Caja chica creada exitosamente!');
              DB::commit();
            return redirect()->route('cajachicas.index')
                ->with('success', 'Caja Chica creada correctamente.');
           
        } catch (QueryException $e) {
            DB::rollBack();
            return back()->with('error', 'No se guardar el asiento de diario' . $e->getMessage());
        }
    }

    /**
     * Formulario para editar Caja Chica
     */
    public function edit($id)
    {
        $caja = CajaChica::findOrFail($id);
        $gestiones = Gestion::all(); // para el select si quieres cambiar de gestión
        return view('AdminSis.cajachicas.edit', compact('caja', 'gestiones'));
    }

    /**
     * Actualizar Caja Chica
     */
    // Actualizar
    public function update(Request $request, $id)
    {
        $request->validate([
            'gestion_id' => 'required|exists:gestiones,id',
        ]);

        $caja = CajaChica::findOrFail($id);
        try {
            DB::beginTransaction();
            $caja->gestion_id = $request->gestion_id;
            $caja->save();
            session()->flash('success', '¡Caja chica actualizada exitosamente!');
            DB::commit();
            return redirect()->route('cajachicas.index')->with('success', 'Caja Chica actualizada correctamente.');
        } catch (QueryException $e) {
            DB::rollBack();
            return back()->with('error', 'No se actualizaron los datos de caja chica' . $e->getMessage());
        }
    }

    /**
     * Eliminar Caja Chica (soft delete)
     */
    public function destroy($id)
    {
        try {
            $caja = CajaChica::findOrFail($id);
            DB::beginTransaction();
            $caja->delete(); // Soft delete
            session()->flash('success', '¡Caja chica eliminada exitosamente!');
            DB::commit();
            return redirect()->route('cajachicas.index')->with('success', 'Caja Chica eliminada correctamente.');
            
        } catch (QueryException $e) {

            DB::rollBack();
            return back()->with('error', 'No se eliminaron los datos de caja chica' . $e->getMessage());
        }
    }
    public function porGestion($gestionId)
    {
        $cajas = CajaChica::where('gestion_id', $gestionId)->select('id', 'nombre')->get();
        return response()->json($cajas);
    }
    public function show($id)
    {
        $caja = CajaChica::with('gestion')->findOrFail($id);
        return response()->json([
            'id' => $caja->id,
            'gestion' => [
                'nombre' => $caja->gestion->nombre,
            ],
            'created_at' => $caja->created_at,
            'updated_at' => $caja->updated_at
        ]);
    }
}

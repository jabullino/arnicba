<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Egreso;
use App\Models\EgresoResidente;
use App\Models\Municipio;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Residente;
use App\Models\Gestion;
use App\Models\MotivoEgreso;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class EgresoResidenteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $motivo=new MotivoEgreso();
        $municipio=new Municipio();
       
        // Cargar todas las derivaciones con el residente
        $egresos = EgresoResidente::with('residente')->orderBy('fecha', 'desc')->get();
        return view('TSocial.Egreso.ListaEgresoResidente', compact('egresos','motivo','municipio'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Obtener todos los residentes para el select
          $residentes = Residente::doesntHave('egreso')->get();
          $municipios = Municipio::all();
          $gestiones = Gestion::all();
          $motivos = MotivoEgreso::all();
        return view('TSocial.Egreso.CreaEgresoResidente', compact('residentes','municipios','gestiones','motivos'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
   
    // VALIDACIÓN
    $validator = Validator::make($request->all(), [
        'residente_id' => 'required|exists:residentes,id',
        'gestion_id' => 'required|exists:gestiones,id',
        'motivo_id' => 'required',
        'destino' => 'nullable|string|max:150|required_if:motivo_id,3,4',
        'municipio_id' => 'required|exists:municipios,id',
        'fecha' => 'required|date',
        'numjuzgado' => 'required|integer',
        'numdoc' => 'required|string|max:50',
        'nomjuez' => 'required|string|max:255',
    ]);

    if ($validator->fails()) {
    return redirect()
        ->back()
        ->withErrors($validator)
        ->withInput();
}

    // 👇 AQUÍ VA (esto faltaba)
    $validated = $validator->validated();
    
    try {
        DB::beginTransaction();

        EgresoResidente::create([
            'residente_id' => $validated['residente_id'],
            'gestion_id' => $validated['gestion_id'],
            'motivo_id' => $validated['motivo_id'],
            'destino' => $validated['destino'],
            'municipio_id' => $validated['municipio_id'],
            'fecha' => Carbon::parse($validated['fecha'])->format('Y-m-d'),
            'numjuzgado' => $validated['numjuzgado'],
            'numdoc' => $validated['numdoc'],
            'nomjuez' => $validated['nomjuez'],
        ]);

        DB::commit();

        return redirect()
            ->route('egresoresidente.index')
            ->with('success', 'Egreso creado correctamente.');

    } catch (\Exception $e) {
        DB::rollBack();

        Log::error('Error al crear el egreso: ' . $e->getMessage());

        return redirect()
            ->back()
            ->withInput()
            ->with('error', 'Ocurrió un error al crear el egreso.');
    }
}


    /**
     * Display the specified resource.
     */
    public function show($id)
{
    try {
        $egreso = EgresoResidente::with('residente')->findOrFail($id);
        $municipio=new Municipio();
        $motivo=new MotivoEgreso();
        return view('TSocial.Egreso.MuestraEgresoResidente', compact('egreso','municipio','motivo'));

    } catch (\Exception $e) {
        Log::error('Error al mostrar el egreso: ' . $e->getMessage());

        return redirect()
            ->route('egresoresidente.index')
            ->with('error', 'No se pudo encontrar el egreso.');
    }
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
{
    try {
        $egreso = EgresoResidente::findOrFail($id);

        // Cargar datos necesarios para selects (ajusta según tu lógica)
        $residentes = Residente::all();
        $gestiones = Gestion::all();
        $municipios = Municipio::all();
        $motivos = MotivoEgreso::all();

        return view('TSocial.Egreso.EditaEgresoResidente', compact(
            'egreso',
            'residentes',
            'gestiones',
            'municipios',
            'motivos'
        ));

    } catch (\Exception $e) {
        Log::error('Error al editar el egreso: ' . $e->getMessage());

        return redirect()
            ->route('egresoresidente.index')
            ->with('error', 'No se pudo cargar el egreso.');
    }
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    
    $validator = Validator::make($request->all(), [
        'residente_id' => 'required|exists:residentes,id',
        'gestion_id' => 'required|exists:gestiones,id',
        'motivo_id' => 'required',
        'destino' => 'nullable|string|max:150|required_if:motivo_id,3,4',
        'municipio_id' => 'required|exists:municipios,id',
        'fecha' => 'required|date',
        'numjuzgado' => 'required|integer',
        'numdoc' => 'required|string|max:50',
        'nomjuez' => 'required|string|max:255',
    ]);

    if ($validator->fails()) {
        return redirect()
            ->back()
            ->withErrors($validator)
            ->withInput();
    }

    $validated = $validator->validated();
    
    try {
        DB::beginTransaction();

        $egreso = EgresoResidente::findOrFail($id);

        $egreso->update([
            'residente_id' => $validated['residente_id'],
            'gestion_id' => $validated['gestion_id'],
            'motivo_id' => $validated['motivo_id'],
            'destino' => $validated['destino'],
            'municipio_id' => $validated['municipio_id'],
            'fecha' => Carbon::parse($validated['fecha'])->format('Y-m-d'),
            'numjuzgado' => $validated['numjuzgado'],
            'numdoc' => $validated['numdoc'],
            'nomjuez' => $validated['nomjuez'],
        ]);

        DB::commit();

        return redirect()
            ->route('egresoresidente.index')
            ->with('success', 'Egreso actualizado correctamente.');

    } catch (\Exception $e) {
        DB::rollBack();

        Log::error('Error al actualizar el egreso: ' . $e->getMessage());

        return redirect()
            ->back()
            ->withInput()
            ->with('error', 'Ocurrió un error al actualizar el egreso.');
    }
}
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
{
    try {
        DB::beginTransaction();

        $egreso = EgresoResidente::findOrFail($id);

        $egreso->delete();

        DB::commit();

        return redirect()
            ->route('egresoresidente.index')
            ->with('success', 'Egreso eliminado correctamente.');

    } catch (\Exception $e) {
        DB::rollBack();

        Log::error('Error al eliminar el egreso: ' . $e->getMessage());

        return redirect()
            ->back()
            ->with('error', 'Ocurrió un error al eliminar el egreso.');
    }
}
}

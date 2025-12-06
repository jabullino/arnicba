<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Derivacion;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Residente;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DerivacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Cargar todas las derivaciones con el residente
        $derivaciones = Derivacion::with('residente')->orderBy('fecha', 'desc')->get();
        return view('TSocial.Derivacion.index', compact('derivaciones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Obtener todos los residentes para el select
          $residentes = Residente::whereDoesntHave('derivacion')->get();
        return view('TSocial.Derivacion.create', compact('residentes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'residente_id' => 'required|exists:users,id',
            'fecha' => 'required|date',
            'numjuzgado' => 'required|string|max:50',
            'numdoc' => 'required|string|max:50',
            'nomjuez' => 'required|string|max:100',
        ]);

        try {
            DB::beginTransaction();
            Derivacion::create([
                'residente_id' => $request->residente_id,
                'fecha' => Carbon::parse($request->fecha)->format('Y-m-d'),
                'numjuzgado' => $request->numjuzgado,
                'numdoc' => $request->numdoc,
                'nomjuez' => $request->nomjuez,
            ]);
            DB::commit();

            return redirect()->route('derivaciones.index')->with('success', 'Derivación creada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear derivación: ' . $e->getMessage());

            return redirect()->back()->withInput()->with('error', 'Ocurrió un error al crear la derivación.');
        }

        return redirect()->route('derivaciones.index')
            ->with('success', 'Derivación creada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Derivacion $derivacion)
    {
        // Cargar el residente relacionado
        $derivacion->load('residente');
        return view('TSocial.Derivacion.show', compact('derivacion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Derivacion $derivacion)
    {
        $derivacion->load('residente'); // Carga explícita de la relación
        $residentes = Residente::orderBy('nombre')->get();
        return view('TSocial.Derivacion.edit', compact('derivacion', 'residentes'));
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, Derivacion $derivacion)
{
    $request->validate([
        'residente_id' => 'required|exists:users,id',
        'fecha' => 'required|date',
        'numjuzgado' => 'required|string|max:50',
        'numdoc' => 'required|string|max:50',
        'nomjuez' => 'required|string|max:100',
    ]);

    try {
        DB::beginTransaction();
        $derivacion->update([
            'residente_id' => $request->residente_id,
            'fecha' => Carbon::parse($request->fecha)->format('Y-m-d'),
            'numjuzgado' => $request->numjuzgado,
            'numdoc' => $request->numdoc,
            'nomjuez' => $request->nomjuez,
        ]);
        DB::commit();

        return redirect()->route('derivaciones.index')->with('success', 'Derivación actualizada correctamente.');
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error al actualizar derivación: '.$e->getMessage());

        return redirect()->back()->withInput()->with('error', 'Ocurrió un error al actualizar la derivación.');
    }
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Derivacion $derivacion)
    {
        $derivacion->delete();
        return redirect()->route('derivaciones.index')
            ->with('success', 'Derivación eliminada correctamente.');
    }
}

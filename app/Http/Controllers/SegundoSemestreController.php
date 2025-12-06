<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gestion;

class SegundoSemestreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         try {
        // Crea la gestión usando tu modelo (disparará creating/created)
        $gestion = Gestion::creaSegundoSemestre($request->salarioMinimo,$request->haberBasico);

        // Mensaje de éxito
        session()->flash('success', "Gestión  creada correctamente.");
    } catch (\Exception $e) {
        // Captura el error y envíalo a la vista
        session()->flash('error', "Error al crear la gestión: " . $e->getMessage());
    }

    // Redirige al index de PanelGestiones
    return redirect()->route('PanelGestiones.index');
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

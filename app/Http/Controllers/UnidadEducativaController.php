<?php

namespace App\Http\Controllers;

use App\Models\UnidadEducativa;
use Illuminate\Http\Request;

class UnidadEducativaController extends Controller
{
    public function index()
    {
        $unidades = UnidadEducativa::all();
        $cont = 1;

        return view('TSocial.Escolaridad.UnidadEducativa', compact('unidades','cont'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255'
        ]);

        UnidadEducativa::create([
            'nombre' => $request->nombre
        ]);

        return redirect()->route('UEducativa.index')
            ->with('success', 'Unidad educativa registrada correctamente.');
    }

    public function edit($id)
    {
        $unidad = UnidadEducativa::findOrFail($id);
        return view('TSocial.Escolaridad.UnidadEducativa.edit', compact('unidad'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255'
        ]);

        $unidad = UnidadEducativa::findOrFail($id);
        $unidad->update([
            'nombre' => $request->nombre
        ]);

        return redirect()->route('UEducativa.index')
            ->with('success', 'Unidad educativa actualizada correctamente.');
    }

    public function destroy($id)
    {
        $unidad = UnidadEducativa::findOrFail($id);
        $unidad->delete();

        return redirect()->route('UEducativa.index')
            ->with('success', 'Unidad educativa eliminada correctamente.');
    }
}

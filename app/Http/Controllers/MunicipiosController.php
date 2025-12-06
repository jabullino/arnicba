<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Municipio;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use App\Models\Ciudad;


class MunicipiosController extends Controller
{

    public function index()
    {

        $ciudades = Ciudad::all();
        return view('TSocial.Municipios.MunicipioLista')->with(['ciudades' => $ciudades]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $ciudades = Ciudad::all();
        return view('TSocial.Municipios.MunicipioCreate')->with(['ciudades' => $ciudades]);
    }

    /**
     * Store a newly created resource in storage.
     */


    public function store(Request $request)
    {
        // Validar que los campos sean requeridos
        $request->validate([
            'ciudad' => 'required|integer',
            'municipio' => 'required|string|max:255',
        ]);

        // Verificar si el nombre ya existe
        $existe = Municipio::where('nombre', $request->nombreMunicipio)->exists();

        if ($existe) {
            // Volver a la plantilla con mensaje de error
            return redirect()->route('Municipios.index')
                ->withInput()
                ->with('error', 'El valor ya existe.');
        }

        // Guardar el registro
        Municipio::create([
            'ciudad_id' => $request->ciudad,
            'nombre' => $request->municipio,
        ]);

        // Redirigir con mensaje de éxito
        return redirect()->route('Municipios.index')
            ->with('success', 'Municipio guardado correctamente.');
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

        $municipio = Municipio::where('id', $id)->first();
        return view('TSocial.Municipios.MunicipioEdit')->with(['municipio' => $municipio]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $ciudades = Ciudad::all();
        $validator = Validator::make($request->all(), [
            'municipios' => 'required',
        ]);

        // Si la validación falla, redirige con los errores
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            DB::beginTransaction();
            $municipio = Municipio::find($id);
            $municipio->nombre = $request->municipios;
            $municipio->save();
            DB::commit();
            session()->flash('success', '¡Municipio Editado exitosamente!');
            return redirect()->route('Municipios.index')->with(['ciudades', $ciudades]);
        } catch (QueryException $e) {
            DB::rollBack();
            return back()->with('error', 'No se pudo editar el municipio' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
{
    try {
        DB::beginTransaction();
        $municipio = Municipio::findOrFail($id);
        $municipio->delete();
        DB::commit();

        return response()->json([
            'success' => true,
            'message' => '¡Municipio eliminado exitosamente!'
        ]);

    } catch (QueryException $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'No se pudo eliminar el municipio: ' . $e->getMessage()
        ], 500);
    }
}

public function getMunicipios($ciudad_id)
{
    $municipios = \App\Models\Municipio::where('ciudad_id', $ciudad_id)
        ->select('id', 'nombre')
        ->orderBy('nombre')
        ->get();

    return response()->json($municipios);
}

}

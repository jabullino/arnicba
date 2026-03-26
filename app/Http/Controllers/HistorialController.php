<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AcogidaCircunstancial;
use Illuminate\Http\Request;
use App\Models\Residente;
use App\Models\Historial;

class HistorialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index()
{
    $historiales = Historial::with('residente')
        ->orderBy('created_at', 'desc')
        ->get();

    return view('TSocial.Historiales.IndexHistorial', compact('historiales'));
}
    /**
     * Show the form for creating a new resource.
     */
public function create()
{
    $residentes = Residente::with([
            'acogida.tipologiaRel',
            'acogida.ciudadRel',
            'acogida.municipioRel'
        ])
        ->whereDoesntHave('historial') // 🔥 CLAVE
        ->orderBy('apellido')
        ->get()
        ->map(function ($residente) {

            $edad = $residente->fecnac
                ? $residente->fecnac->age
                : null;

            $fechaIngresoRaw = $residente->acogida?->fecha;

            $fechaIngresoFormateada = $fechaIngresoRaw
                ? \Carbon\Carbon::parse($fechaIngresoRaw)->format('d-m-Y')
                : null;

            $estadia = null;

            if ($fechaIngresoRaw) {
                $fechaIngreso = \Carbon\Carbon::parse($fechaIngresoRaw);
                $ahora = \Carbon\Carbon::now();

                $diff = $fechaIngreso->diff($ahora);

                $estadia = "{$diff->y} años, {$diff->m} meses y {$diff->d} días";
            }

            return [
                'id' => $residente->id,
                'nombre' => $residente->nombre,
                'apellido' => $residente->apellido,
                'fecnac' => optional($residente->fecnac)->format('d-m-Y'),
                'edad' => $edad,
                'fecha_ingreso' => $fechaIngresoFormateada,
                'estadia' => $estadia,
                'tipologia' => $residente->acogida?->tipologiaRel?->nombre,
                'ciudad' => $residente->acogida?->ciudadRel?->nombre,
                'municipio' => $residente->acogida?->municipioRel?->nombre,
            ];
        });

    return view('TSocial.Historiales.RegistraHistorial', compact('residentes'));
}
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'residente_id' => 'required|exists:residentes,id',
            'titulo' => 'required|string|max:255',
            'contenido' => 'required|string',
        ]);

        Historial::create([
            'residente_id' => $request->residente_id,
            'titulo' => $request->titulo,
            'contenido' => $request->contenido,
        ]);

        return redirect()
            ->route('historiales.create')
            ->with('success', 'Historial registrado correctamente.');
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
        $historial = Historial::findOrFail($id);

        $residentes = Residente::with([
            'acogida.tipologiaRel',
            'acogida.ciudadRel',
            'acogida.municipioRel'
        ])
            ->orderBy('apellido')
            ->get()
            ->map(function ($residente) {

                $edad = $residente->fecnac ? $residente->fecnac->age : null;

                $fechaIngresoRaw = $residente->acogida?->fecha;

                $fechaIngresoFormateada = $fechaIngresoRaw
                    ? \Carbon\Carbon::parse($fechaIngresoRaw)->format('d-m-Y')
                    : null;

                $estadia = null;

                if ($fechaIngresoRaw) {
                    $fechaIngreso = \Carbon\Carbon::parse($fechaIngresoRaw);
                    $diff = $fechaIngreso->diff(\Carbon\Carbon::now());
                    $estadia = "{$diff->y} años, {$diff->m} meses y {$diff->d} días";
                }

                return [
                    'id' => $residente->id,
                    'nombre' => $residente->nombre,
                    'apellido' => $residente->apellido,
                    'fecnac' => optional($residente->fecnac)->format('d-m-Y'),
                    'edad' => $edad,
                    'fecha_ingreso' => $fechaIngresoFormateada,
                    'estadia' => $estadia,
                    'tipologia' => $residente->acogida?->tipologiaRel?->nombre,
                    'ciudad' => $residente->acogida?->ciudadRel?->nombre,
                    'municipio' => $residente->acogida?->municipioRel?->nombre,
                ];
           });

        return view('TSocial.Historiales.EditaHistorial', compact('historial', 'residentes'));
    }

    /**
     * Update the specified resource in storage.
     */
public function update(Request $request, $id)
{
    $request->validate([
        'residente_id' => 'required|exists:residentes,id',
        'titulo' => 'required|string|max:255',
        'contenido' => 'required|string',
    ]);

    $historial = Historial::findOrFail($id);

    $historial->update([
        'residente_id' => $request->residente_id,
        'titulo' => $request->titulo,
        'contenido' => $request->contenido,
    ]);

    return redirect()
        ->route('historiales.edit', $historial->id)
        ->with('success', 'Historial actualizado correctamente.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

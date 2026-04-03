<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\HaberBasico;
use Illuminate\Http\Request;
use App\Models\Gestion;
use Illuminate\Support\Facades\DB;
use App\Models\SalarioMinimo;

class IncrementoSalarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index()
{
    // Subconsulta
    $sub = DB::table('haber_basicos as hb2')
        ->select('hb2.cargo_id', DB::raw('MAX(hb2.created_at) as max_fecha'))
        ->whereNull('hb2.deleted_at') // ✅ aquí corregido
        ->groupBy('hb2.cargo_id');

    // Consulta principal
    $haberes = DB::table('haber_basicos as hb')
        ->joinSub($sub, 'ultimos', function ($join) {
            $join->on('hb.cargo_id', '=', 'ultimos.cargo_id')
                 ->on('hb.created_at', '=', 'ultimos.max_fecha');
        })
        ->join('cargos as c', 'c.id', '=', 'hb.cargo_id')
        ->whereNull('hb.deleted_at') // ✅ aquí corregido
        ->whereNull('c.deleted_at')  // ✅ importante si cargos usa soft delete
        ->select(
            'c.id as cargo_id',
            'c.nombre as cargo_nombre',
            'hb.monto'
        )
        ->get();

    // Gestiones
    $gestiones = Gestion::select('id', 'nombre')->get();

    return view('AdminSis.IncrementoSalario', compact('haberes', 'gestiones'));
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
    $request->validate([
        'incremento' => 'required|numeric',
        'salario_minimo' => 'required|numeric',
        'gestion_id' => 'required|exists:gestiones,id',
        'cargos' => 'required|array|min:1',
        'cargos.*' => 'exists:cargos,id'
    ]);

    $incremento = $request->incremento;
    $salarioMinimo = $request->salario_minimo;
    $gestionId = $request->gestion_id;

    // 🔒 Filtrar cargos válidos desde BD
    $cargosSeleccionados = DB::table('cargos')
        ->whereIn('id', $request->cargos)
        ->pluck('id')
        ->toArray();

    DB::beginTransaction();

    try {

        // 🔹 Subconsulta: último salario por cargo
        $sub = DB::table('haber_basicos as hb2')
            ->select('hb2.cargo_id', DB::raw('MAX(hb2.created_at) as max_fecha'))
            ->whereNull('hb2.deleted_at')
            ->groupBy('hb2.cargo_id');

        // 🔹 Obtener salarios actuales
        $haberes = DB::table('haber_basicos as hb')
            ->joinSub($sub, 'ultimos', function ($join) {
                $join->on('hb.cargo_id', '=', 'ultimos.cargo_id')
                     ->on('hb.created_at', '=', 'ultimos.max_fecha');
            })
            ->whereNull('hb.deleted_at')
            ->whereIn('hb.cargo_id', $cargosSeleccionados)
            ->select('hb.cargo_id', 'hb.monto')
            ->get();

        // 🔹 Aplicar incremento
        foreach ($haberes as $item) {

            $nuevoMonto = $item->monto + ($item->monto * ($incremento / 100));

            // Aplicar salario mínimo
            if ($nuevoMonto < $salarioMinimo) {
                $nuevoMonto = $salarioMinimo;
            }

            HaberBasico::create([
                'gestion_id' => $gestionId,
                'cargo_id' => $item->cargo_id,
                'monto' => $nuevoMonto,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // 🔹 Guardar salario mínimo
        SalarioMinimo::create([
            'gestion_id' => $gestionId,
            'monto' => $salarioMinimo,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::commit();

        return redirect()
            ->route('incrementosalarial.index')
            ->with('success', 'Incremento aplicado correctamente');

    } catch (\Exception $e) {

        DB::rollBack();

        return back()->with('error', $e->getMessage());
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

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lote;
use App\Models\OrigenFondos;
use Illuminate\Support\Facades\DB;

class LoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $lotes = DB::table('lotes')
        ->join('productos', 'productos.id', '=', 'lotes.producto_id')
        ->select(
            'lotes.*',
            'productos.nombre as producto_nombre',
            'productos.codigo as producto_codigo',
            'productos.marca as producto_marca'
        )
        ->where('lotes.saldo', '>', 0)
        ->orderBy('lotes.producto_id', 'asc')      // Agrupa por producto
        ->orderBy('lotes.created_at', 'asc')       // Más antiguo primero
        ->paginate(10);

    return view('Almacen.Lotes.index', compact('lotes'));
}



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $origenes = OrigenFondos::all();
        return view('Almacen.Lotes.RegistrarLote')->with(['origenes' => $origenes]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // VALIDACIONES
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'codigo'      => 'required|string|max:50',
            'cantidad'    => 'required|numeric|min:0.01',
            'precio'      => 'required|numeric|min:0',
            'fec_venc'    => 'nullable|date',
            'origen_id'   => 'required|integer|exists:origen_fondos,id',
        ]);

        DB::beginTransaction();

        try {

            DB::table('lotes')->insert([
                'producto_id' => $request->producto_id,
                'codigo'      => $request->codigo,
                'cantidad'    => $request->cantidad,
                'saldo'       => $request->cantidad,
                'precio'      => $request->precio,
                'fec_venc'    => $request->fec_venc,
                'fec_ingre'   => now(), // 👈 AGREGA ESTO
                'origen_id'   => $request->origen_id,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Lote registrado correctamente');
        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()->back()->with(
                'error',
                'Ocurrió un error al guardar el lote: ' . $e->getMessage()
            );
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
        $lote = DB::table('lotes')
            ->join('productos', 'productos.id', '=', 'lotes.producto_id')
            ->select(
                'lotes.*',
                'productos.nombre as producto_nombre',
                'productos.codigo as producto_codigo',
                'productos.marca as producto_marca'
            )
            ->where('lotes.id', $id)
            ->first();

        $origenes = DB::table('origen_fondos')->get();

        if (!$lote) {
            return redirect()->route('Lote.index')
                ->with('error', 'Lote no encontrado');
        }

        return view('Almacen.Lotes.edit', compact('lote', 'origenes'));
    }

    /**
     * Update the specified resource in storage.
     */
     public function update(Request $request, $id)
    {
        $request->validate([
            'codigo'    => 'required|string|max:50',
            'cantidad'  => 'required|numeric|min:0.01',
            'precio'    => 'required|numeric|min:0',
            'fec_venc'  => 'nullable|date',
            'origen_id' => 'required|integer|exists:origen_fondos,id',
        ]);

        DB::beginTransaction();

        try {

            $lote = DB::table('lotes')->where('id', $id)->first();

            if (!$lote) {
                return redirect()->back()->with('error', 'Lote no encontrado');
            }

            $nuevoSaldo = $request->cantidad - ($lote->cantidad - $lote->saldo);

            DB::table('lotes')->where('id', $id)->update([
                'codigo'     => $request->codigo,
                'cantidad'   => $request->cantidad,
                'saldo'      => $nuevoSaldo,
                'precio'     => $request->precio,
                'fec_venc'   => $request->fec_venc,
                'origen_id'  => $request->origen_id,
                'updated_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('Lote.index')
                ->with('success', 'Lote actualizado correctamente');

        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
        public function destroy($id)
    {
        DB::beginTransaction();

        try {

            DB::table('lotes')->where('id', $id)->delete();

            DB::commit();

            return redirect()->route('Lote.index')
                ->with('success', 'Lote eliminado correctamente');

        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }
}


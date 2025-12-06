<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asiento;
use App\Models\Gestion;
use App\Models\TipoCambioCompra;
use App\Models\TipoCambioVenta;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class PagaServiciosController extends Controller
{

    public function index(){

        return view('Administrador.FormPagaServicios');
    }
   public function guardar(Request $request)
{
   
    $filas = $request->input('data');

    foreach ($filas as $index => $fila) {
        $validator = Validator::make($fila, [
            'fecha' => 'required|date|before_or_equal:today',
            'factura' => 'required|numeric',
            'valor' => ['required', 'regex:/^(\d{1,3})(?:,\d{3})*(\.\d{1,2})?$/'],
            'opcion_id' => 'required',
        ], [
            'fecha.required' => "La fecha en la fila #$index es obligatoria.",
            'factura.required' => "La factura en la fila #$index es obligatoria.",
            'valor.required' => "El valor en la fila #$index es obligatorio.",
            'opcion_id.required' => "Debe seleccionar una opción en la fila #$index.",
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with(['data' => $filas]);
        }
    }

    // Lógica para guardar (sin cambios)
    $tipocambiocompra_id = TipoCambiocompra::latest()->value('id');
    $tc = TipoCambiocompra::where('id', $tipocambiocompra_id)->value('tc');
    $gestion_id = Gestion::latest()->value('id');
   try{
    db::beginTransaction();
    foreach ($filas as $fila) {
        $valorsus = $fila['valor'] / $tc;

        Asiento::create([
            'gestion_id' => $gestion_id,
            'fec_asiento' => $fila['fecha'],
            'tc_id' => $tipocambiocompra_id,
            'tv_id' => $tipocambiocompra_id,
            'recibo' => null,
            'factura' => $fila['factura'],
            'cuenta' => 1,
            'sub_cuenta' => $fila['opcion_id'],
            'monto_bs' => $fila['valor'],
            'monto_sus' => $valorsus,
            'origenfondos_id' => 1,
            'tipomovimiento_id' => 1,
            'estado_id' => 1,
        ]);
    }
    session()->flash('success', '¡Servicios pagados exitosamente!'); 
    DB::commit();
    return redirect()->back()->with('success', 'Asientos guardados correctamente.');
    
}catch (QueryException $e) {
            DB::rollBack();
            return back()->with('error', 'No se pudieron pagar los servicios' . $e->getMessage());
        }
}

    
    
}

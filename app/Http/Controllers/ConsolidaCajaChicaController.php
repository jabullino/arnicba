<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EntregasCajaChica;
use App\Models\GastosCajaChica;
use App\Models\Asiento;
use App\Models\TipoCambioCompra;
use App\Models\TipoCambioVenta;
use App\Models\Gestion;
use App\Models\Cuenta;
use App\Models\SubCuenta;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ConsolidaCajaChicaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        // Solo retorna la vista vacía con el saldo disponible
        return view('AdminSis.ConsolidacionCajaChica.FormListaPagos');
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
    $tipocambiocompra_id = TipoCambioCompra::latest()->value('id');
    $tc = TipoCambioCompra::where('id', $tipocambiocompra_id)->value('tc') ?? 1;
    $tipocambioventa_id = TipoCambioVenta::latest()->value('id');
    $gestion_id = Gestion::latest()->value('id');
    $proyecto_id = null;
    $estado_id = 1;

    $escogidos = $request->input('escogidos');
    if (empty($escogidos)) {
        return back()->with('error', 'No seleccionaste ningún gasto.');
    }

    DB::beginTransaction();
    try {
        foreach ($escogidos as $id) {
            $gasto = GastosCajaChica::with(['cuenta', 'subcuenta'])->find($id);
            if (!$gasto) continue; // 🔹 Ignorar si no existe

            $factura = $gasto->factura ?: null;
            $recibo = $gasto->recibo ?: null;

            if ($factura) {
                $docField = 'factura';
                $docValue = $factura;
                $recibo = null;
            } else {
                $docField = 'recibo';
                $docValue = $recibo;
                $factura = null;
            }

            $cuenta_id = $gasto->cuenta_id;
            $subcuenta_id = $gasto->subcuenta_id;
            $fecha_doc = $gasto->fecha_doc;
            $fecha_registro = $gasto->fecha_registro ?? null; // evita error si no existe
            $importe_bs = $gasto->importe;
            $importe_sus = $importe_bs / $tc;

            Asiento::create([
                'gestion_id'         => $gestion_id,
                'fec_asiento'        => $fecha_doc,
                'tc_id'              => $tipocambiocompra_id,
                'tv_id'              => $tipocambioventa_id,
                'recibo'             => $recibo,
                'factura'            => $factura,
                'cuenta'             => $cuenta_id,
                'sub_cuenta'         => $subcuenta_id,
                'monto_bs'           => $importe_bs,
                'monto_sus'          => $importe_sus,
                'origenfondos_id'    => 1,
                'tipomovimiento_id'  => 1,
                'proyecto_id'        => $proyecto_id,
                'estado_id'          => $estado_id,
            ]);

            // 🔹 Actualizar estado de gastos relacionados
            if (empty($docValue)) {
                $gasto->update(['status' => 'consolidado']);
            } else {
                $query = GastosCajaChica::where($docField, $docValue)
                    ->where('fecha_doc', $fecha_doc);
                
                if ($fecha_registro) $query->where('fecha_registro', $fecha_registro);
                
                $query->update(['status' => 'consolidado']);
            }
        }

        DB::commit();
        return back()->with('success', '¡Asiento creado exitosamente y gastos consolidados!');

    } catch (\Throwable $e) {
        DB::rollBack();
        // 🔹 Solo loguea el error real, pero no lo muestra al usuario si todo se creó
        Log::error("Error real al crear asientos: ".$e->getMessage(), [
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ]);

        return back()->with('error', 'Ocurrió un error inesperado al crear los asientos.');
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


    public function obtenerPagos(Request $request)
    {
        try {
            $anio = $request->anio;
            $mes = $request->mes;

            // Traer todos los registros pendientes sin eliminar
            $gastos = GastosCajaChica::with(['cuenta', 'subcuenta'])
                ->where('status', 'pendiente')
                ->whereNull('deleted_at')
                ->when($anio, fn($q) => $q->whereYear('fecha_doc', $anio))
                ->when($mes, fn($q) => $q->whereMonth('fecha_doc', $mes))
                ->orderBy('fecha_doc', 'desc')
                ->get();

            // Agrupar por fecha, cuenta, subcuenta y tipo de documento (Factura o Recibo)
            $gastosAgrupados = $gastos->groupBy(function ($item) {
                $tipoDocumento = (!empty($item->factura)) ? 'Factura' : 'Recibo';
                return $item->fecha_doc . '-' . $item->cuenta_id . '-' . $item->subcuenta_id . '-' . $tipoDocumento;
            })->map(function ($group) {
                $first = $group->first();

                // Quitar duplicados en los números de documento (factura o recibo)
                $documentos = $group->map(fn($g) => $g->factura ?: $g->recibo)
                    ->filter() // quita null o vacíos
                    ->unique()
                    ->implode(', ');

                $first->documentos = $documentos ?: '—';
                $first->tipo_documento = (!empty($first->factura)) ? 'Factura' : 'Recibo';
                $first->importe = $group->sum('importe');

                return $first;
            })->values();

            $html = view('AdminSis.ConsolidacionCajaChica.partials.tabla_gastos', [
                'gastos' => $gastosAgrupados
            ])->render();

            return response()->json(['html' => $html]);
        } catch (\Throwable $e) {
            Log::error('Error obtenerGastos: ' . $e->getMessage(), [
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return response()->json(['html' => ''], 500);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EntregasCajaChica;
use App\Models\Cuenta;
use App\Models\SubCuenta;
use App\Models\GastosCajaChica;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\EntregasCajaChicaController;


class GastosCajaChicaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $disponible = EntregasCajaChica::sum('saldo');
        $gastos = GastosCajaChica::with(['cuenta', 'subcuenta'])->get();
        // Solo retorna la vista vacía con el saldo disponible
        return view('Administrador.CajaChica.FormListaGastos', compact('disponible'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $total = EntregasCajaChica::sum('saldo');
        $cuentas = Cuenta::whereIn('cod_cuentas', ['200', '200', '400', '500', '700', '800', '900', '1000', '1400', '1500'])
            ->get(['id', 'nombre']);

        return view('Administrador.CajaChica.FormNuevoPago')->with(['disponible' => $total, 'cuentas' => $cuentas]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1️⃣ Validación de campos
        $request->validate([
            'fecha' => 'required|date',
            'importe' => 'required|numeric|min:0.01',
            'cuenta' => 'required|exists:cuentas,id',
            'subcuenta' => 'required|exists:sub_cuentas,id',
            'factura' => 'nullable|string|max:15',
            'recibo' => 'nullable|string|max:15',
        ]);

        // 2️⃣ Convertimos el importe a número limpio (por si llega con coma o punto)
        $importeARetirar = floatval(str_replace(',', '', $request->importe));

        // 3️⃣ Obtener entregas activas con saldo > 0
        $entregas = EntregasCajaChica::whereNull('deleted_at')
            ->where('saldo', '>', 0)
            ->orderBy('id', 'asc')
            ->get();

        // 4️⃣ Calcular el saldo total disponible
        $saldoDisponible = $entregas->sum('saldo');

        // 🔍 Debug opcional
        // dd(['importe' => $importeARetirar, 'saldo_disponible' => $saldoDisponible]);

        // 5️⃣ Validar que el importe no exceda el saldo total
        if ($importeARetirar > $saldoDisponible) {
            return back()->with('error', 'El importe ingresado supera el saldo disponible.');
        }

        DB::beginTransaction();

        try {
            foreach ($entregas as $entrega) {
                if ($importeARetirar <= 0) break;

                $montoARestar = 0;

                if ($entrega->saldo <= $importeARetirar) {
                    $montoARestar = $entrega->saldo;
                    $entrega->saldo = 0;
                    $entrega->deleted_at = now(); // opcional: marcar como agotada
                } else {
                    $montoARestar = $importeARetirar;
                    $entrega->saldo -= $montoARestar;
                }

                $entrega->save();

                // 6️⃣ Registrar el gasto correspondiente
                GastosCajaChica::create([
                    'entregas_id' => $entrega->id,
                    'cuenta_id' => $request->cuenta,
                    'subcuenta_id' => $request->subcuenta,
                    'fecha_doc' => $request->fecha,
                    'fecha_registro' => now(),
                    'factura' => $request->factura ?? '',
                    'recibo' => $request->recibo ?? '',
                    'importe' => $montoARestar,
                    'status' => 'pendiente',
                ]);

                $importeARetirar -= $montoARestar;
            }

            DB::commit();

            return back()->with('success', 'El retiro se ha registrado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Ocurrió un error al registrar el retiro: ' . $e->getMessage());
        }
    }



    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Buscar el gasto con su entrega, cuenta y subcuenta
        $gasto = GastosCajaChica::with(['cuenta', 'subcuenta', 'entrega'])
            ->findOrFail($id);

        return view('Administrador.CajaChica.FormVisualizaPagos', compact('gasto'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $cuentas = Cuenta::whereIn('cod_cuentas', ['200', '200', '400', '500', '700', '800', '900', '1000', '1400', '1500'])
            ->get(['id', 'nombre']);
        $gasto = GastosCajaChica::findOrFail($id);
        $subcuenta = SubCuenta::where('id', $gasto->subcuenta_id)->value('nombre');
        $nomcuenta = Cuenta::where('id', $gasto->cuenta_id)->value('nombre');
        return view('Administrador.CajaChica.FormEditaGastos', compact('gasto', 'cuentas', 'nomcuenta', 'subcuenta'));
    }

    // Actualizar
    public function update(Request $request, $id)
    {
        // 1️⃣ Validación de datos
        $request->validate([
            'fecha_doc' => 'required|date',
            'factura' => 'nullable|string|max:50',
            'recibo' => 'nullable|string|max:50',
            'cuenta' => 'required|exists:cuentas,id',
            'subcuenta' => 'nullable|exists:sub_cuentas,id',
            'importe' => 'required|numeric|min:0',
        ]);

        // 2️⃣ Buscar el gasto
        $gasto = GastosCajaChica::findOrFail($id);

        if (is_null($gasto->entregas_id)) {
            return redirect()->back()->with('error', 'El gasto no tiene entrega asociada.');
        }

        $entregasId = $gasto->entregas_id;

        // 3️⃣ Obtener la entrega directamente desde DB (incluyendo soft-deleted)
        $entregaRow = DB::table('entregas_caja_chicas')->where('id', $entregasId)->first();
        if (!$entregaRow) {
            return redirect()->back()->with('error', "No se encontró la entrega asociada al gasto.");
        }

        // 4️⃣ Restaurar entrega si tiene deleted_at distinto de null
        if (!is_null($entregaRow->deleted_at)) {
            DB::table('entregas_caja_chicas')->where('id', $entregasId)->update(['deleted_at' => null]);
            // Refrescamos el objeto
            $entregaRow = DB::table('entregas_caja_chicas')->where('id', $entregasId)->first();
        }

        // 5️⃣ Calcular nuevo saldo
        $importeAnterior = (float) $gasto->importe;
        $importeNuevo = (float) $request->importe;
        $saldoActual = (float) $entregaRow->saldo;

        $nuevoSaldo = $saldoActual - ($importeNuevo - $importeAnterior);

        if ($nuevoSaldo < 0) {
            return redirect()->back()->with('error', 'El importe excede el saldo disponible.');
        }

        // 6️⃣ Actualizar dentro de una transacción
        DB::beginTransaction();
        try {
            // Actualizamos gasto
            $gasto->update([
                'fecha_doc' => $request->fecha_doc,
                'factura' => $request->factura ?? '',
                'recibo' => $request->recibo ?? '', // <-- nunca null
                'cuenta_id' => $request->cuenta,
                'subcuenta_id' => $request->subcuenta,
                'importe' => number_format($importeNuevo, 2, '.', ''),
            ]);

            // Actualizamos saldo de la entrega
            DB::table('entregas_caja_chicas')
                ->where('id', $entregasId)
                ->update([
                    'saldo' => number_format($nuevoSaldo, 2, '.', ''),
                    'updated_at' => now(),
                ]);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error actualizando gasto/entrega', [
                'gasto_id' => $gasto->id,
                'entregas_id' => $entregasId,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()->with('error', 'Ocurrió un error al actualizar. Revisa los logs.');
        }

        return redirect()->route('gastoscajachica.index')
            ->with('success', 'Pago actualizado correctamente y saldo ajustado.');
    }

   public function destroy($id)
{
    // 1️⃣ Buscar el gasto
    $gasto = GastosCajaChica::findOrFail($id);

    if (is_null($gasto->entregas_id)) {
        return redirect()->back()->with('error', 'El gasto no tiene entrega asociada.');
    }

    $entregasId = $gasto->entregas_id;

    // 2️⃣ Obtener la entrega directamente desde DB (incluyendo soft-deleted)
    $entregaRow = DB::table('entregas_caja_chicas')->where('id', $entregasId)->first();
    if (!$entregaRow) {
        return redirect()->back()->with('error', "No se encontró la entrega asociada al gasto.");
    }

    // 3️⃣ Restaurar entrega si tiene deleted_at distinto de null
    if (!is_null($entregaRow->deleted_at)) {
        DB::table('entregas_caja_chicas')
            ->where('id', $entregasId)
            ->update(['deleted_at' => null]);
        // Refrescamos el objeto
        $entregaRow = DB::table('entregas_caja_chicas')->where('id', $entregasId)->first();
    }

    // 4️⃣ Calcular el nuevo saldo sumando el importe del gasto eliminado
    $importeGasto = (float) $gasto->importe;
    $saldoActual = (float) $entregaRow->saldo;
    $nuevoSaldo = $saldoActual + $importeGasto;

    // 5️⃣ Transacción segura
    DB::beginTransaction();
    try {
        // a) Actualizar el status del gasto a "eliminado"
        $gasto->status = 'eliminado';
        $gasto->save();

        // b) Eliminar (SoftDelete) el gasto
        $gasto->delete();

        // c) Actualizar el saldo de la entrega
        DB::table('entregas_caja_chicas')
            ->where('id', $entregasId)
            ->update([
                'saldo' => number_format($nuevoSaldo, 2, '.', ''),
                'updated_at' => now(),
            ]);

        DB::commit();
    } catch (\Throwable $e) {
        DB::rollBack();
        Log::error('Error eliminando gasto/actualizando entrega', [
            'gasto_id' => $gasto->id,
            'entregas_id' => $entregasId,
            'error' => $e->getMessage(),
        ]);

        return redirect()->back()->with('error', 'Ocurrió un error al eliminar el pago. Revisa los logs.');
    }

    return redirect()->route('gastoscajachica.index')
        ->with('success', 'Pago eliminado correctamente y saldo de la entrega ajustado.');
}



public function obtenerGastos(Request $request)
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

        // Agrupar por fecha, cuenta, subcuenta y tipo de documento
        $gastosAgrupados = $gastos->groupBy(function($item) {
            $tipoDocumento = $item->factura ? 'Factura' : 'Recibo';
            return $item->fecha_doc.'-'.$item->cuenta_id.'-'.$item->subcuenta_id.'-'.$tipoDocumento;
        })->map(function($group) {
            $first = $group->first();

            $first->documentos = $group->map(fn($g) => $g->factura ?? $g->recibo)->implode(', ');
            $first->importe = $group->sum('importe');
            return $first;
        })->values();

        $html = view('Administrador.gastoscajachica.partials.tabla_gastos', ['gastos' => $gastosAgrupados])->render();

        return response()->json(['html' => $html]);

    } catch (\Throwable $e) {
        Log::error('Error obtenerGastos: '.$e->getMessage(), [
            'line' => $e->getLine(),
            'file' => $e->getFile(),
        ]);

        return response()->json(['html' => ''], 500);
    }
}



}

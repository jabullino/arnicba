<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Egreso;
use App\Models\EgresoDetalle;
use App\Models\Lote;
use App\Models\Destinatario;


class EgresosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $destinatarios=Destinatario::all();
        return view('Almacen.Egresos.RegistrarEgreso')->with(['destinatarios'=>$destinatarios]);
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
    
    if (!$request->destinatario || $request->destinatario === 'default') {
        return back()->with('error','Debe seleccionar un destinatario.');
    }

    if (!$request->carrito) {
        return back()->with('error','Carrito vacío.');
    }

    $carrito = json_decode($request->carrito, true);

    if (!$carrito || count($carrito) === 0) {
        return back()->with('error','Debe agregar productos.');
    }

    DB::beginTransaction();

    try {

        $egreso = Egreso::create([
            'fecha' => now()->toDateString(),
            'destinatario_id' => $request->destinatario
        ]);

        foreach ($carrito as $item) {

            $productoId = $item['id'];
            $cantidadSolicitada = (int) $item['cantidad'];

            $lotes = Lote::where('producto_id', $productoId)
                        ->whereNull('deleted_at')
                        ->where('saldo', '>', 0)
                        ->orderBy('created_at', 'asc')
                        ->lockForUpdate()
                        ->get();

            if ($lotes->isEmpty()) {
                throw new \Exception("No hay lotes disponibles.");
            }

            $saldoTotal = $lotes->sum('saldo');

            if ($saldoTotal < $cantidadSolicitada) {
                throw new \Exception("Saldo insuficiente. Disponible: {$saldoTotal}");
            }

            $cantidadRestante = $cantidadSolicitada;

            foreach ($lotes as $lote) {

                if ($cantidadRestante <= 0) break;

                if ($lote->saldo >= $cantidadRestante) {

                    $lote->saldo -= $cantidadRestante;

                    if ($lote->saldo == 0) {
                        $lote->deleted_at = now();
                    }

                    $lote->save();
                    $cantidadRestante = 0;

                } else {

                    $cantidadRestante -= $lote->saldo;

                    $lote->saldo = 0;
                    $lote->deleted_at = now();
                    $lote->save();
                }
            }

            EgresoDetalle::create([
                'egreso_id' => $egreso->id,
                'producto_id' => $productoId,
                'cantidad' => $cantidadSolicitada
            ]);
        }

        DB::commit();

        return redirect()->route('Egresos.index')
            ->with('success','Egreso registrado correctamente.');

    } catch (\Throwable $e) {

        DB::rollBack();

        dd($e->getMessage()); // TEMPORAL PARA VER ERROR REAL
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

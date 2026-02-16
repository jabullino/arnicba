<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Egreso;
use App\Models\EgresoDetalle;
use App\Models\Lote;
use App\Models\Destinatario;
use Barryvdh\DomPDF\Facade\Pdf;


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

       return redirect()->route('egresos.pdf', $egreso->id);

    } catch (\Throwable $e) {

        DB::rollBack();

        dd($e->getMessage()); // TEMPORAL PARA VER ERROR REAL
    }
}


public function pdf($id)
{
    $egreso = Egreso::with(['destinatario', 'detalles.producto'])
        ->findOrFail($id);

    $productosFormateados = [];

    foreach ($egreso->detalles as $detalle) {

        $info = app(\App\Http\Controllers\ProductoController::class)
            ->obtenerNombreProducto(
                $detalle->producto_id,
                $detalle->producto->categoria_id
            );

        $productosFormateados[] = [
            'nombre'   => $info['nombre'],
            'cantidad' => $detalle->cantidad
        ];
    }

    // Convertir logo a base64
    $path = public_path('imagenes/Logo.png');

    if (!file_exists($path)) {
        dd('No existe el logo en: '.$path);
    }

    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);
    $logo = 'data:image/' . $type . ';base64,' . base64_encode($data);

    return Pdf::loadView('Almacen.Egresos.pdf', [
            'egreso' => $egreso,
            'productosFormateados' => $productosFormateados,
            'logo' => $logo
        ])
        ->setPaper('letter')
        ->setOptions([
            'defaultFont' => 'DejaVu Sans'
        ])
        ->stream('Egreso_'.$egreso->id.'.pdf', ['Attachment' => false]);
} /**
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

    public function print($id)
{
    $egreso = Egreso::with(['destinatario', 'detalles.producto'])
        ->findOrFail($id);

    return view('egresos.print', compact('egreso'));
}

    
}

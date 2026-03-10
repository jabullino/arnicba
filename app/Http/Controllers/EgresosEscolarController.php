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
use App\Models\Producto;


class EgresosEscolarController extends Controller
{

    public function index()
    {
        $egresos = Egreso::with(['destinatario', 'egresoDetalles.producto'])
            ->whereHas('egresoDetalles.producto', function ($q) {
                $q->whereIn('categoria_id', [4, 7]);
            })
            ->whereDoesntHave('egresoDetalles.producto', function ($q) {
                $q->whereNotIn('categoria_id', [4, 7]);
            })
            ->orderBy('id', 'desc')
            ->get();

        return view('Administrador.MaterialEscolar.Egresos.MostrarEgresosEscolar', compact('egresos'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $destinatarios = Destinatario::all();
        return view('Administrador.MaterialEscolar.Egresos.RegistrarEgresoEscolar')->with(['destinatarios' => $destinatarios]);
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $request->validate([
            'destinatario' => 'required|exists:destinatarios,id',
            'carrito' => 'required'
        ]);

        $carrito = json_decode($request->carrito, true);

        if (!$carrito || count($carrito) === 0) {
            return back()->with('error', 'Debe agregar al menos un producto.');
        }

        DB::beginTransaction();

        try {

            $egreso = Egreso::create([
                'fecha' => now(),
                'destinatario_id' => $request->destinatario
            ]);

            foreach ($carrito as $item) {

                $producto = Producto::lockForUpdate()->findOrFail($item['id']);

                if ($producto->saldo < $item['cantidad']) {
                    throw new \Exception("Stock insuficiente para {$producto->nombre}");
                }

                // Crear detalle
                EgresoDetalle::create([
                    'egreso_id' => $egreso->id,
                    'producto_id' => $producto->id,
                    'cantidad' => $item['cantidad']
                ]);

                // 🔥 ACTUALIZAR STOCK CORRECTAMENTE
                $producto->decrement('saldo', $item['cantidad']);
                $producto->increment('lineas');
            }

            DB::commit();

            return $this->pdf($egreso->id);
        } catch (\Exception $e) {

            DB::rollBack();
            return back()->with('error', $e->getMessage());
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
            dd('No existe el logo en: ' . $path);
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
            ->stream('Egreso_' . $egreso->id . '.pdf', ['Attachment' => false]);
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
        $egreso = Egreso::with('detalles.producto', 'destinatario')
            ->findOrFail($id);

        $destinatarios = Destinatario::orderBy('nombre')->get();

        return view('Administrador.MaterialEscolar.Egresos.EditarEgresoEscolar', compact('egreso', 'destinatarios'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'destinatario' => 'required|exists:destinatarios,id',
            'carrito' => 'required'
        ]);

        $carrito = json_decode($request->carrito, true);

        if (!$carrito || count($carrito) === 0) {
            return back()->with('error', 'Debe agregar al menos un producto.');
        }

        DB::beginTransaction();

        try {

            $egreso = Egreso::with('detalles.producto')->findOrFail($id);

            // 🔄 DEVOLVER SOLO EL SALDO (NO lineas)
            foreach ($egreso->detalles as $detalle) {
                $detalle->producto->increment('saldo', $detalle->cantidad);
            }

            // ❌ ELIMINAR DETALLES ANTIGUOS
            EgresoDetalle::where('egreso_id', $egreso->id)->delete();

            // ✏ ACTUALIZAR CABECERA
            $egreso->update([
                'destinatario_id' => $request->destinatario,
            ]);

            // ➖ INSERTAR NUEVOS DETALLES Y DESCONTAR SALDO
            foreach ($carrito as $item) {

                $producto = Producto::lockForUpdate()->findOrFail($item['id']);

                if ($producto->saldo < $item['cantidad']) {
                    throw new \Exception("Stock insuficiente para {$producto->nombre}");
                }

                EgresoDetalle::create([
                    'egreso_id' => $egreso->id,
                    'producto_id' => $producto->id,
                    'cantidad' => $item['cantidad']
                ]);

                // 🔥 SOLO AFECTAMOS SALDO
                $producto->decrement('saldo', $item['cantidad']);
            }

            DB::commit();

            return redirect()->route('EgresosEscolar.index')
                ->with('success', 'Egreso actualizado correctamente.');
        } catch (\Exception $e) {

            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {

            $egreso = Egreso::with('detalles.producto')->findOrFail($id);

            foreach ($egreso->detalles as $detalle) {
                $detalle->producto->increment('saldo', $detalle->cantidad);
            }

            $egreso->delete();

            DB::commit();

            return back()->with('success', 'Egreso eliminado correctamente.');
        } catch (\Exception $e) {

            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function print($id)
    {
        $egreso = Egreso::with(['destinatario', 'detalles.producto'])
            ->findOrFail($id);

        return view('egresos.print', compact('egreso'));
    }

    public function listarEgresos()
    {
        $egresos = Egreso::with(['destinatario', 'egresoDetalles.producto'])
            ->whereHas('egresoDetalles.producto', function ($q) {
                $q->whereIn('categoria_id', [4, 7]);
            })
            ->whereDoesntHave('egresoDetalles.producto', function ($q) {
                $q->whereNotIn('categoria_id', [4, 7]);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('Administrador.MaterialEscolar.Egresos.ImprimirEgresosEscolar', compact('egresos'));
    }
}

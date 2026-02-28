<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\OrigenFondos;
use Illuminate\Support\Facades\DB;
use App\Models\Ingreso;
use App\Models\DetalleIngreso;
use Barryvdh\DomPDF\Facade\Pdf;

class IngresosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ingresos = Ingreso::with('origen')
            ->orderBy('created_at', 'desc') // Ordena por fecha de creación
            ->paginate(10);

        return view('Almacen.Ingresos.EditarIngreso', compact('ingresos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $productos = Producto::all();
        $origenFondos = OrigenFondos::all();

        return view('Almacen.Ingresos.RegistrarIngreso', compact('productos', 'origenFondos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date',
            'origen_fondo_id' => 'required|exists:origen_fondos,id',

            // Validación: al menos uno obligatorio
            'factura' => 'nullable|string|max:50|required_without:recibo',
            'recibo'  => 'nullable|string|max:50|required_without:factura',

            'detalles' => 'required|array|min:1',
            'detalles.*.producto_id' => 'required|exists:productos,id',
            'detalles.*.cantidad' => 'required|numeric|min:0.01',
            'detalles.*.precio' => 'required|numeric|min:0.01',
            'detalles.*.fecha_vencimiento' => 'nullable|date',
        ], [
            'factura.required_without' => 'Debe ingresar factura o recibo.',
            'recibo.required_without' => 'Debe ingresar factura o recibo.',
            'detalles.required' => 'Debe agregar al menos un producto al carrito.',
        ]);

        // Evitar ambos campos al mismo tiempo
        if ($request->filled('factura') && $request->filled('recibo')) {
            return back()
                ->withErrors(['factura' => 'No puede ingresar factura y recibo al mismo tiempo.'])
                ->withInput();
        }

        DB::beginTransaction();

        try {

            // Crear ingreso
            $ingreso = Ingreso::create([
                'fecha' => $request->fecha,
                'factura' => $request->factura,
                'recibo' => $request->recibo,
                'origen_id' => $request->origen_fondo_id
            ]);

            foreach ($request->detalles as $detalle) {

                // Crear detalle
                $ingreso->detalles()->create([
                    'producto_id' => $detalle['producto_id'],
                    'cantidad' => $detalle['cantidad'],
                    'precio' => $detalle['precio'],
                    'vencimiento' => $detalle['fecha_vencimiento'] ?? null,
                ]);

                // ✅ Actualizar saldo correctamente
                $producto = Producto::find($detalle['producto_id']);

                if ($producto) {
                    $producto->increment('saldo', (float) $detalle['cantidad']);
                }
            }

            DB::commit();

            // Redirigir al PDF recién creado
            return redirect()->route('ingresos.pdf', $ingreso->id);
        } catch (\Exception $e) {

            DB::rollBack();
            dd($e->getMessage());
            return back()
                ->with('error', 'Ocurrió un error al registrar el ingreso.')
                ->withInput();
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
        $ingreso = Ingreso::with('detalles.producto')
            ->findOrFail($id);

        $origenFondos = OrigenFondos::all();

        return view(
            'Almacen.Ingresos.DetalleIngreso',
            compact('ingreso', 'origenFondos')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'fecha' => 'required|date',
            'origen_fondo_id' => 'required|exists:origen_fondos,id',

            'factura' => 'nullable|string|max:50|required_without:recibo',
            'recibo'  => 'nullable|string|max:50|required_without:factura',

            'detalles' => 'required|array|min:1',
            'detalles.*.producto_id' => 'required|exists:productos,id',
            'detalles.*.cantidad' => 'required|numeric|min:0.01',
            'detalles.*.precio' => 'required|numeric|min:0.01',
            'detalles.*.fecha_vencimiento' => 'nullable|date',
        ], [
            'factura.required_without' => 'Debe ingresar factura o recibo.',
            'recibo.required_without' => 'Debe ingresar factura o recibo.',
            'detalles.required' => 'Debe agregar al menos un producto al carrito.',
        ]);

        if ($request->filled('factura') && $request->filled('recibo')) {
            return back()
                ->withErrors(['factura' => 'No puede ingresar factura y recibo al mismo tiempo.'])
                ->withInput();
        }

        DB::beginTransaction();

        try {

            $ingreso = Ingreso::with('detalles')->findOrFail($id);

            /*
        |--------------------------------------------------------------------------
        | 1️⃣ RESTAR SALDO ANTERIOR
        |--------------------------------------------------------------------------
        */
            foreach ($ingreso->detalles as $detalleAnterior) {

                $producto = Producto::find($detalleAnterior->producto_id);

                if ($producto) {
                    $producto->decrement('saldo', (float) $detalleAnterior->cantidad);
                }
            }

            /*
        |--------------------------------------------------------------------------
        | 2️⃣ ELIMINAR DETALLES ANTERIORES
        |--------------------------------------------------------------------------
        */
            $ingreso->detalles()->delete();

            /*
        |--------------------------------------------------------------------------
        | 3️⃣ ACTUALIZAR ENCABEZADO
        |--------------------------------------------------------------------------
        */
            $ingreso->update([
                'fecha' => $request->fecha,
                'factura' => $request->factura,
                'recibo' => $request->recibo,
                'origen_id' => $request->origen_fondo_id
            ]);

            /*
        |--------------------------------------------------------------------------
        | 4️⃣ CREAR NUEVOS DETALLES Y SUMAR SALDO
        |--------------------------------------------------------------------------
        */
            foreach ($request->detalles as $detalle) {

                $ingreso->detalles()->create([
                    'producto_id' => $detalle['producto_id'],
                    'cantidad' => $detalle['cantidad'],
                    'precio' => $detalle['precio'],
                    'vencimiento' => $detalle['fecha_vencimiento'] ?? null,
                ]);

                $producto = Producto::find($detalle['producto_id']);

                if ($producto) {
                    $producto->increment('saldo', (float) $detalle['cantidad']);
                }
            }

            DB::commit();

            return redirect()->route('ingresos.pdf', $ingreso->id);
        } catch (\Exception $e) {

            DB::rollBack();
            dd($e->getMessage());

            return back()
                ->with('error', 'Ocurrió un error al actualizar el ingreso.')
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {

            $ingreso = Ingreso::with('detalles')->findOrFail($id);

            // 🔥 Revertir saldo de productos
            foreach ($ingreso->detalles as $detalle) {

                $producto = Producto::find($detalle->producto_id);

                if ($producto) {
                    $producto->decrement('saldo', (float) $detalle->cantidad);
                }
            }

            // Eliminar detalles primero (por seguridad)
            $ingreso->detalles()->delete();

            // Eliminar ingreso
            $ingreso->delete();

            DB::commit();

            return redirect()
                ->route('Ingresos.index')
                ->with('success', 'Ingreso eliminado correctamente');
        } catch (\Exception $e) {

            DB::rollBack();

            return back()
                ->with('error', 'Ocurrió un error al eliminar el ingreso.');
        }
    }

    public function pdfIngreso($id)
    {
        $ingreso = Ingreso::with(['origen', 'detalles.producto'])
            ->findOrFail($id);

        $productosFormateados = [];

        foreach ($ingreso->detalles as $detalle) {

            $info = app(\App\Http\Controllers\ProductoController::class)
                ->obtenerNombreProducto(
                    $detalle->producto_id,
                    $detalle->producto->categoria_id
                );

            $productosFormateados[] = [
                'nombre'   => $info['nombre'],
                'cantidad' => $detalle->cantidad,
                'precio'   => $detalle->precio,
                'subtotal' => $detalle->cantidad * $detalle->precio
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

        return Pdf::loadView('Almacen.Ingresos.pdf', [
            'ingreso' => $ingreso,
            'productosFormateados' => $productosFormateados,
            'logo' => $logo
        ])
            ->setPaper('letter')
            ->setOptions([
                'defaultFont' => 'DejaVu Sans'
            ])
            ->stream('Ingreso_' . $ingreso->id . '.pdf', ['Attachment' => false]);
    }

    public function listarIngresos()
    {
        $ingresos = Ingreso::with('origen')
            ->orderBy('created_at', 'desc') // Ordena por fecha de creación
            ->paginate(10);

        return view('Almacen.Ingresos.ImprimirIngresos', compact('ingresos'));
    }
}

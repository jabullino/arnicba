<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\OrigenFondos;
use Illuminate\Support\Facades\DB;
use App\Models\Ingreso;
use App\Models\DetalleIngreso;
use Barryvdh\DomPDF\Facade\Pdf;

class IngresoEscolarController extends Controller
{
    public function index()
    {
        $ingresos = Ingreso::with(['origen', 'detalleIngresos.producto'])
            ->whereHas('detalleIngresos.producto', function ($q) {
                $q->whereIn('categoria_id', [4, 7]);
            })
            ->whereDoesntHave('detalleIngresos.producto', function ($q) {
                $q->whereNotIn('categoria_id', [4, 7]);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('Administrador.MaterialEscolar.Ingresos.EditarIngresoEscolar', compact('ingresos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $productos = Producto::all();
        $origenFondos = OrigenFondos::all();

        return view('Administrador.MaterialEscolar.Ingresos.RegistrarIngresoEscolar', compact('productos', 'origenFondos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date|before_or_equal:today',
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
            return redirect()->route('ingresosescolar.pdf', $ingreso->id);
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
            'Administrador.MaterialEscolar.Ingresos.DetalleIngresoEscolar',
            compact('ingreso', 'origenFondos')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'fecha' => 'required|date|before_or_equal:today',
            'origen_fondo_id' => 'required|exists:origen_fondos,id',

            'factura' => 'nullable|string|max:50|required_without:recibo',
            'recibo'  => 'nullable|string|max:50|required_without:factura',

            'detalles' => 'required|array|min:1',
            'detalles.*.id' => 'required|exists:detalle_ingresos,id',
            'detalles.*.producto_id' => 'required|exists:productos,id',
            'detalles.*.cantidad' => 'required|numeric|min:0.01',
            'detalles.*.precio' => 'required|numeric|min:0.01',
            'detalles.*.fecha_vencimiento' => 'nullable|date',
        ]);

        DB::beginTransaction();

        try {

            $ingreso = Ingreso::findOrFail($id);

            /*
        |---------------------------------------
        | ACTUALIZAR ENCABEZADO
        |---------------------------------------
        */
            $ingreso->update([
                'fecha' => $request->fecha,
                'factura' => $request->factura,
                'recibo' => $request->recibo,
                'origen_id' => $request->origen_fondo_id
            ]);

            /*
        |---------------------------------------
        | ACTUALIZAR DETALLES
        |---------------------------------------
        */
            foreach ($request->detalles as $detalle) {

                $detalleDB = $ingreso->detalles()->where('id', $detalle['id'])->first();

                if ($detalleDB) {

                    // ajustar saldo si cambió cantidad
                    $diferencia = $detalle['cantidad'] - $detalleDB->cantidad;

                    $producto = Producto::find($detalle['producto_id']);

                    if ($producto && $diferencia != 0) {
                        $producto->increment('saldo', $diferencia);
                    }

                    $detalleDB->update([
                        'producto_id' => $detalle['producto_id'],
                        'cantidad' => $detalle['cantidad'],
                        'precio' => $detalle['precio'],
                        'vencimiento' => $detalle['fecha_vencimiento'] ?? null,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('ingresosescolar.pdf', $ingreso->id);
        } catch (\Exception $e) {

            DB::rollBack();
            dd($e->getMessage());
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
                ->route('IngresoEscolar.index')
                ->with('success', 'Ingreso eliminado correctamente');
        } catch (\Exception $e) {

            DB::rollBack();

            return back()
                ->with('error', 'Ocurrió un error al eliminar el ingreso.');
        }
    }

    public function pdfIngresoEscolar($id)
    {
        $ingreso = Ingreso::with(['origen', 'detalles.producto'])
            ->findOrFail($id);

        // Formatear fecha a dd-mm-aaaa
        $ingreso->fecha = \Carbon\Carbon::parse($ingreso->fecha)->format('d-m-Y');

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
        $ingresos = Ingreso::with(['origen', 'detalleIngresos.producto'])
            ->whereHas('detalleIngresos.producto', function ($q) {
                $q->whereIn('categoria_id', [4, 7]);
            })
            ->whereDoesntHave('detalleIngresos.producto', function ($q) {
                $q->whereNotIn('categoria_id', [4, 7]);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view(
            'Administrador.MaterialEscolar.Ingresos.ImprimirIngresosEscolar',
            compact('ingresos')
        );
    }
}

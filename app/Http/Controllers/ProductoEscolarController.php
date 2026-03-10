<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Producto;
use Database\Seeders\CategoriaSeeder;
use App\Models\Color;
use App\Models\Talla;
use App\Models\Tallazapato;
use App\Models\Categoria;
use App\Models\Presentacion;
use App\Models\Capacidad;
use App\Models\Unidad;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProductoEscolarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productos = Producto::whereIn('categoria_id', [4, 7])
            ->paginate(10);

        $prod = new Producto();

        return view('Administrador.MaterialEscolar.Productos.ProductosEscolar')->with([
            'productos' => $productos,
            'prod'      => $prod
        ]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorias = Categoria::whereIn('id', [4, 7])->get();
        $presentaciones = Presentacion::all();
        $capacidades = Capacidad::all();
        $unidades = Unidad::all();
        $colores = Color::all();

        return view('Administrador.MaterialEscolar.Productos.RegistrarProductoEscolar')->with(['categorias' => $categorias, 'presentaciones' => $presentaciones, 'capacidades' => $capacidades, 'unidades' => $unidades, 'colores' => $colores]);
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {

        // VALIDACIONES BASE
        $rules = [
            'categoria_id'     => 'required|integer',
            'nombre'           => 'required|string|max:30',
            'marca'            => 'required|string|max:50',
            'codigo'           => 'required|string|max:50',
            'saldo'            => 'numeric|decimal:2|between:-99999.99,99999.99',
            'presentacion_id'  => 'required|integer',
            'capacidad_id'     => 'required|integer',
            'unidad_id'        => 'required|integer',
        ];

        // TELA (4)
        if ($request->categoria_id == 4) {
            $rules['color_id'] = 'required|integer';
            $rules['largo']    = 'required|numeric';
            $rules['ancho']    = 'required|numeric';
        }

        // COLOR (5)
        if ($request->categoria_id == 7) {
            $rules['color_id'] = 'nullable|integer';
        }



        Validator::make($request->all(), $rules)->validate();

        DB::beginTransaction();

        try {
            $productoId = DB::table('productos')->insertGetId([
                'nombre'       => $request->nombre,
                'marca'        => $request->marca,
                'codigo'       => $request->codigo,
                'categoria_id' => $request->categoria_id,
                'lineas'       => 0,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);

            DB::table('presentacion_producto')->insert([
                'presentacion_id' => $request->presentacion_id,
                'producto_id'     => $productoId,
            ]);

            DB::table('capacidad_producto')->insert([
                'capacidad_id' => $request->capacidad_id,
                'producto_id'  => $productoId,
            ]);

            DB::table('producto_unidad')->insert([
                'producto_id' => $productoId,
                'unidad_id'   => $request->unidad_id,
            ]);

            // TELA
            if ($request->categoria_id == 4) {
                DB::table('telas')->insert([
                    'producto_id' => $productoId,
                    'color_id'    => $request->color_id,
                    'largo'       => $request->largo,
                    'ancho'       => $request->ancho,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            }

            // VESTIMENTA
            if ($request->categoria_id == 7 && $request->color_id != 0) {
                DB::table('color_producto')->insert([
                    'producto_id' => $productoId,
                    'color_id'    => $request->color_id,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                    'deleted_at'  => null,
                ]);
            }

            DB::commit();

            return redirect()->back()->with('success', 'Producto registrado correctamente');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with(
                'error',
                'Ocurrió un error al guardar el producto: ' . $e->getMessage()
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
        // PRODUCTO BASE
        $producto = DB::table('productos')->where('id', $id)->first();

        if (!$producto) {
            abort(404);
        }

        // PRESENTACION
        $presentacion = DB::table('presentacion_producto')
            ->where('producto_id', $id)
            ->first();

        $producto->presentacion_id = $presentacion->presentacion_id ?? null;

        // CAPACIDAD
        $capacidad = DB::table('capacidad_producto')
            ->where('producto_id', $id)
            ->first();

        $producto->capacidad_id = $capacidad->capacidad_id ?? null;

        // UNIDAD
        $unidad = DB::table('producto_unidad')
            ->where('producto_id', $id)
            ->first();

        $producto->unidad_id = $unidad->unidad_id ?? null;

        // ================= CATEGORIAS ESPECIALES =================

        // TELA (4)
        if ($producto->categoria_id == 4) {

            $tela = DB::table('telas')
                ->where('producto_id', $id)
                ->first();

            if ($tela) {
                $producto->color_id = $tela->color_id;
                $producto->largo    = $tela->largo;
                $producto->ancho    = $tela->ancho;
            }
        }

        // COLOR (7)
        if ($producto->categoria_id == 7) {

            $color = DB::table('color_producto')
                ->where('producto_id', $id)
                ->first();

            if ($color) {
                $producto->color_id = $color->color_id;
                $producto->id = $producto->id;
            }
        }


        // CATÁLOGOS
        $categorias     = Categoria::whereIn('id', [4, 7])->get();
        $presentaciones = Presentacion::all();
        $capacidades    = Capacidad::all();
        $unidades       = Unidad::all();
        $colores        = Color::all();

        return view('Administrador.MaterialEscolar.Productos.EditarProductoEscolar', compact(
            'producto',
            'categorias',
            'presentaciones',
            'capacidades',
            'unidades',
            'colores',
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
       $rules = [
    'categoria_id'     => 'required|integer',
    'nombre'           => 'required|string|max:30',
    'marca'            => 'required|string|max:50',
    'codigo'           => 'required|string|max:50',
    'presentacion_id'  => 'required|integer',
    'capacidad_id'     => 'required|integer',
    'unidad_id'        => 'required|integer',
];

            // ===== TELA (categoria 4) =====
if ($request->categoria_id == 4) {
    $rules['color_id'] = 'required|integer';
    $rules['largo']    = 'required|numeric';
    $rules['ancho']    = 'required|numeric';
}

// ===== MATERIAL ESCOLAR (categoria 7) =====
if ($request->categoria_id == 7) {
    $rules['color_id'] = 'nullable|integer';
}

        Validator::make($request->all(), $rules)->validate();

        DB::beginTransaction();

        try {
            $producto = Producto::findOrFail($id);
            $categoriaAnterior = $producto->categoria_id;

            $producto->update([
                'nombre'       => $request->nombre,
                'marca'        => $request->marca,
                'codigo'       => $request->codigo,
                'categoria_id' => $request->categoria_id,
            ]);



            DB::table('presentacion_producto')->where('producto_id', $id)
                ->update(['presentacion_id' => $request->presentacion_id]);

            DB::table('capacidad_producto')->where('producto_id', $id)
                ->update(['capacidad_id' => $request->capacidad_id]);

            DB::table('producto_unidad')->where('producto_id', $id)
                ->update(['unidad_id' => $request->unidad_id]);

            // LIMPIAR RELACIONES ANTERIORES SI CAMBIÓ CATEGORÍA
            if ($categoriaAnterior != $request->categoria_id) {
                DB::table('telas')->where('producto_id', $id)->delete();
                DB::table('color_producto')->where('producto_id', $id)->delete();
            }

            // INSERTAR NUEVA CATEGORÍA
            if ($request->categoria_id == 4) {
                DB::table('telas')->updateOrInsert(
                    ['producto_id' => $id],
                    ['color_id' => $request->color_id, 'largo' => $request->largo, 'ancho' => $request->ancho]
                );
            }

            if ($request->categoria_id == 7 && $request->color_id != 0) {
                DB::table('color_producto')->updateOrInsert(
                    ['producto_id' => $id],
                    ['color_id' => $request->color_id]
                );
            }

            DB::commit();

            return redirect()->route('ProductoEscolar.index')
                ->with('success', 'Producto actualizado correctamente');
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
        $producto = Producto::findOrFail($id);

        // Relaciones según categoría
        if ($producto->categoria_id == 4) {
            DB::table('telas')
                ->where('producto_id', $id)
                ->delete();
        }

        if ($producto->categoria_id == 7) {
            DB::table('color_producto')
                ->where('producto_id', $id)
                ->delete();
        }

        // Tablas pivote
        DB::table('presentacion_producto')->where('producto_id', $id)->delete();
        DB::table('capacidad_producto')->where('producto_id', $id)->delete();
        DB::table('producto_unidad')->where('producto_id', $id)->delete();

        // Soft delete solo del producto
        $producto->delete();

        DB::commit();

        return redirect()
            ->route('ProductoEscolar.index')
            ->with('success', 'Producto eliminado correctamente');

    } catch (\Exception $e) {

        DB::rollBack();

        return back()->with('error', 'Error al eliminar el producto: ' . $e->getMessage());
    }
}


    public function buscarItemEscolar(Request $request)
    {
        $q = trim($request->q);

        if (!$q) {
            return response()->json([]);
        }

        $productos = DB::table('productos')
            ->where(function ($query) use ($q) {
                $query->where('nombre', 'like', "{$q}%")
                    ->orWhere('marca', 'like', "{$q}%");
            })
            ->limit(10)
            ->get();

        $resultado = [];

        foreach ($productos as $producto) {

            $info = $this->obtenerNombreProductoEscolar(
                $producto->id,
                $producto->categoria_id
            );

            $resultado[] = [
                'id'     => $producto->id,
                'nombre' => $info['nombre'],
                'saldo'  => $producto->saldo, // 👈 AQUÍ ESTABA EL PROBLEMA
            ];
        }

        return response()->json($resultado);
    }

    public function obtenerNombreProductoEscolar($id, $categoria)
    {
        // ================= PRODUCTO BASE =================
        $producto = DB::table('productos')->where('id', $id)->first();

        if (!$producto) {
            return [
                'id'     => $id,
                'nombre' => '',
                'saldo'  => 0
            ];
        }

        // Nombre base SIEMPRE
        $nombre = trim($producto->nombre . ' ' . $producto->marca);

        /*
    |------------------------------------------------------------------
    | CATEGORÍAS 1, 2 y 3 (PRODUCTO NORMAL)
    |------------------------------------------------------------------
    */
        if (in_array($categoria, [1, 2, 3])) {

            $presentacion = DB::table('presentacion_producto')
                ->join('presentaciones', 'presentaciones.id', '=', 'presentacion_producto.presentacion_id')
                ->where('presentacion_producto.producto_id', $id)
                ->value('presentaciones.nombre');

            $capacidad = DB::table('capacidad_producto')
                ->join('capacidades', 'capacidades.id', '=', 'capacidad_producto.capacidad_id')
                ->where('capacidad_producto.producto_id', $id)
                ->value('capacidades.nombre');

            $unidad = DB::table('producto_unidad')
                ->join('unidades', 'unidades.id', '=', 'producto_unidad.unidad_id')
                ->where('producto_unidad.producto_id', $id)
                ->value('unidades.nombre');

            $nombre = trim(
                $nombre . ' ' .
                    ($presentacion ?? '') . ' ' .
                    ($capacidad ?? '') . ' ' .
                    ($unidad ?? '')
            );
        }

        /*
    |------------------------------------------------------------------
    | CATEGORÍA 4 (TELAS)
    |------------------------------------------------------------------
    */ elseif ($categoria == 4) {

            $tela = DB::table('telas')->where('producto_id', $id)->first();

            if ($tela) {

                $color = DB::table('colores')
                    ->where('id', $tela->color_id)
                    ->value('nombre');

                $nombre = trim(
                    $nombre . ' ' .
                        ($tela->ancho ?? '') . ' ' .
                        ($tela->largo ?? '') . ' ' .
                        ($color ?? '')
                );
            }
        }

        /*

    |------------------------------------------------------------------
    | CATEGORÍA 7 (COLOR)
    |------------------------------------------------------------------
    */ elseif ($categoria == 7) {

            $color = DB::table('color_producto')->where('producto_id', $id)->first();

            if ($color) {

                $color = DB::table('colores')
                    ->where('id', $color)
                    ->value('nombre');
            }
        }

        /*
    |------------------------------------------------------------------
    | SALDO (TODAS LAS CATEGORÍAS)
    |------------------------------------------------------------------
    */
        $saldo = DB::table('lotes')
            ->where('producto_id', $id)
            ->where('saldo', '>', 0)
            ->sum('saldo');

        return [
            'id'     => $id,
            'nombre' => trim($nombre),
            'saldo'  => $saldo
        ];
    }
}

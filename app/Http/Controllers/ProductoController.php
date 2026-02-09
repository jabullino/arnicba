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

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index()
{
    $productos = Producto::where('categoria_id','!=','6')
                    ->paginate(10);

    $prod = new Producto();

    return view('Almacen.Productos.Productos')->with([
        'productos' => $productos,
        'prod'      => $prod
    ]);
}
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       $categorias=Categoria::all();
       $presentaciones=Presentacion::all();
       $capacidades=Capacidad::all();
       $unidades=Unidad::all();
       $colores=Color::all();
       $tallas=Talla::all();
       $tallazapatos=Tallazapato::all();
       return view('Almacen.Productos.RegistrarProducto')->with(['categorias'=>$categorias,'presentaciones'=>$presentaciones,'capacidades'=>$capacidades,'unidades'=>$unidades,'colores'=>$colores,'tallas'=>$tallas,'tallazapatos'=>$tallazapatos]);
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

    // VESTIMENTA (5)
    if ($request->categoria_id == 5) {
        $rules['color_id'] = 'required|integer';
        $rules['talla_id'] = 'required|integer';
    }

    // ZAPATOS (6)
    if ($request->categoria_id == 6) {
        $rules['color_id']       = 'required|integer';
        $rules['tallazapato_id'] = 'required|integer';
    }

    Validator::make($request->all(), $rules)->validate();

    DB::beginTransaction();

    try {
        $productoId = DB::table('productos')->insertGetId([
            'nombre'       => $request->nombre,
            'marca'        => $request->marca,
            'codigo'       => $request->codigo,
            'lineas'       => 1,
            'categoria_id' => $request->categoria_id,
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
        if ($request->categoria_id == 5) {
            DB::table('vestimenta')->insert([
                'producto_id' => $productoId,
                'talla_id'    => $request->talla_id,
                'color_id'    => $request->color_id,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }

        // ZAPATOS
        if ($request->categoria_id == 6) {
            DB::table('zapatos')->insert([
                'producto_id' => $productoId,
                'talla_id'    => $request->tallazapato_id,
                'color_id'    => $request->color_id,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }

        DB::commit();

        return redirect()->back()->with('success', 'Producto registrado correctamente');

    } catch (\Exception $e) {
        DB::rollBack();

       return redirect()->back()->with('error',
    'Ocurrió un error al guardar el producto: ' . $e->getMessage()
);
    }
}    /**
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
    $producto = Producto::findOrFail($id);

    $categorias     = Categoria::all();
    $presentaciones = Presentacion::all();
    $capacidades    = Capacidad::all();
    $unidades       = Unidad::all();
    $colores        = Color::all();
    $tallas         = Talla::all();
    $tallazapatos   = Tallazapato::all();

    return view('Almacen.Productos.EditarProducto')->with([
        'producto'        => $producto,
        'categorias'      => $categorias,
        'presentaciones'  => $presentaciones,
        'capacidades'     => $capacidades,
        'unidades'        => $unidades,
        'colores'         => $colores,
        'tallas'          => $tallas,
        'tallazapatos'    => $tallazapatos,
    ]);
}

    /**
     * Update the specified resource in storage.
     */
 public function update(Request $request, $id)
{
    $rules = [
    // BASE
    'categoria_id'     => 'required|integer',
    'nombre'           => 'required|string|max:30',
    'marca'            => 'required|string|max:50',
    'codigo'           => 'required|string|max:50',
    'presentacion_id'  => 'required|integer',
    'capacidad_id'     => 'required|integer',
    'unidad_id'        => 'required|integer',

    // ===== TELA (4) =====
    'color_id' => 'exclude_unless:categoria_id,4,5,6|required|integer',
    'largo'    => 'exclude_unless:categoria_id,4|required|numeric',
    'ancho'    => 'exclude_unless:categoria_id,4|required|numeric',

    // ===== VESTIMENTA (5) =====
    'talla_id' => 'exclude_unless:categoria_id,5|required|integer',

    // ===== ZAPATOS (6) =====
    'tallazapato_id' => 'exclude_unless:categoria_id,6|required|integer',
];

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
            DB::table('vestimentas')->where('producto_id', $id)->delete();
            DB::table('zapatos')->where('producto_id', $id)->delete();
        }

        // INSERTAR NUEVA CATEGORÍA
        if ($request->categoria_id == 4) {
            DB::table('telas')->updateOrInsert(
                ['producto_id' => $id],
                ['color_id'=>$request->color_id,'largo'=>$request->largo,'ancho'=>$request->ancho]
            );
        }

        if ($request->categoria_id == 5) {
            DB::table('vestimentas')->updateOrInsert(
                ['producto_id' => $id],
                ['talla_id'=>$request->talla_id,'color_id'=>$request->color_id]
            );
        }

        if ($request->categoria_id == 6) {
            DB::table('zapatos')->updateOrInsert(
                ['producto_id' => $id],
                ['talla_id'=>$request->tallazapato_id,'color_id'=>$request->color_id]
            );
        }

        DB::commit();

        return redirect()->route('Producto.index')
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
        // Buscar producto
        $producto = Producto::findOrFail($id);

        // Eliminar relaciones según categoría
        if ($producto->categoria_id == 4) {
            DB::table('telas')->where('producto_id', $id)->delete();
        }

        if ($producto->categoria_id == 5) {
            DB::table('vestimenta')->where('producto_id', $id)->delete();
        }

        if ($producto->categoria_id == 6) {
            DB::table('zapatos')->where('producto_id', $id)->delete();
        }

        // Tablas pivote
        DB::table('presentacion_producto')->where('producto_id', $id)->delete();
        DB::table('capacidad_producto')->where('producto_id', $id)->delete();
        DB::table('producto_unidad')->where('producto_id', $id)->delete();

        // Eliminar producto
        DB::table('productos')->where('id', $id)->delete();

        DB::commit();

        return redirect()
            ->route('Producto.index')
            ->with('success', 'Producto eliminado correctamente');

    } catch (\Exception $e) {
        DB::rollBack();

        return redirect()
            ->back()
            ->with('error', 'Error al eliminar el producto: ' . $e->getMessage());
    }
}

}

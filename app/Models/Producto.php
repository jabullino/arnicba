<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;


class Producto extends Model
{
    use SoftDeletes, HasFactory;
    protected $fillable = ['nombre', 'marca', 'codigo', 'saldo', 'lineas'];
    protected $attributes = [
        'saldo' => 0,
    ];

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function vestimentas(): HasMany
    {
        return $this->hasMany(Vestimenta::class);
    }

    public function telas(): HasMany
    {
        return $this->hasMany(Tela::class);
    }

    public function zapatos(): HasMany
    {
        return $this->hasMany(Zapato::class);
    }

    public function lotes(): HasMany
    {
        return $this->hasMany(Lote::class);
    }

    public function presentaciones(): BelongsToMany
    {
        return $this->belongsToMany(Presentacion::class);
    }

    public function unidades(): BelongsToMany
    {
        return $this->belongsToMany(Unidad::class);
    }

    public function capacidades(): BelongsToMany
    {
        return $this->belongsToMany(Capacidad::class);
    }

    public function egresos(): BelongsToMany
    {
        return $this->belongsToMany(Egreso::class);
    }

    public function detallesIngreso()
    {
        return $this->hasMany(DetalleIngreso::class);
    }

    public function presentacion()
    {
        return $this->belongsToMany(Presentacion::class, 'presentacion_producto');
    }

    public function capacidad()
    {
        return $this->belongsToMany(Capacidad::class, 'capacidad_producto');
    }

    public function unidad()
    {
        return $this->belongsToMany(Unidad::class, 'producto_unidad');
    }

    public function tela()
    {
        return $this->hasOne(Tela::class);
    }

    public function vestimenta()
    {
        return $this->hasOne(Vestimenta::class);
    }

    public function zapato()
    {
        return $this->hasOne(Zapato::class);
    }


    public function obtenerNombreProducto($id, $categoria)
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

        // ✅ Nombre base SIN código
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
    | CATEGORÍA 5 (VESTIMENTA)
    |------------------------------------------------------------------
    */ elseif ($categoria == 5) {

            $vestimenta = DB::table('vestimentas')->where('producto_id', $id)->first();

            if ($vestimenta) {
                $talla = DB::table('tallas')
                    ->where('id', $vestimenta->talla_id)
                    ->value('nombre');

                $color = DB::table('colores')
                    ->where('id', $vestimenta->color_id)
                    ->value('nombre');

                $nombre = trim(
                    $nombre . ' ' .
                        ($talla ?? '') . ' ' .
                        ($color ?? '')
                );
            }
        }

        /*
    |------------------------------------------------------------------
    | CATEGORÍA 6 (ZAPATOS)
    |------------------------------------------------------------------
    */ elseif ($categoria == 6) {

            $zapato = DB::table('zapatos')->where('producto_id', $id)->first();

            if ($zapato) {
                $tallaZapato = DB::table('tallazapatos')
                    ->where('id', $zapato->talla_id)
                    ->value('nombre');

                $color = DB::table('colores')
                    ->where('id', $zapato->color_id)
                    ->value('nombre');

                $nombre = trim(
                    $nombre . ' ' .
                        ($tallaZapato ?? '') . ' ' .
                        ($color ?? '')
                );
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
            'nombre' => $nombre,
            'saldo'  => $saldo
        ];
    }

    public function egresoDetalles()
    {
        return $this->hasMany(EgresoDetalle::class);
    }

    public function devuelveUnidades()
    {
        return $this->belongsToMany(
            Unidad::class,
            'producto_unidad',
            'producto_id',
            'unidad_id'
        );
    }

    public function productos()
    {
        return $this->belongsToMany(
            Producto::class,
            'producto_unidad',
            'unidad_id',
            'producto_id'
        );
    }

    public static function obtenerUnidadNombre($productoId)
    {
        $producto = self::with('devuelveUnidades')->find($productoId);

        if (!$producto || $producto->devuelveUnidades->isEmpty()) {
            return null;
        }

        return $producto->devuelveUnidades->first()->nombre;
    }

    public function colores(): BelongsToMany
    {
        return $this->belongsToMany(Color::class);
    }
    protected static function booted()
    {
        // Siempre excluir registros eliminados (deleted_at no nulo)
        static::addGlobalScope('not_deleted', function ($query) {
            $query->whereNull('deleted_at');
        });
    }
}

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
    use SoftDeletes,HasFactory;
    protected $fillable=['nombre','marca','codigo','lineas'];

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

   

public function obtenerNombreProducto($id, $categoria)
{
    // 1️⃣ Obtener datos base del producto
    $producto = DB::table('productos')
        ->where('id', $id)
        ->first();

    if (!$producto) {
        return null;
    }

    $nombre = '';
    $saldo  = 0;

    /*
    |--------------------------------------------------------------------------
    | CATEGORÍAS 1 Y 2 (PRODUCTO NORMAL)
    |--------------------------------------------------------------------------
    */
    if ($categoria == 1 || $categoria == 2) {

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
            $producto->codigo . ' ' .
            $producto->nombre . ' ' .
            $producto->marca . ' ' .
            ($presentacion ?? '') . ' ' .
            ($capacidad ?? '') . ' ' .
            ($unidad ?? '')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CATEGORÍA 3 (TELAS)
    |--------------------------------------------------------------------------
    */
    elseif ($categoria == 3) {

        $tela = DB::table('telas')
            ->where('producto_id', $id)
            ->first();

        $extraTela = '';

        if ($tela) {
            $color = DB::table('colores')
                ->where('id', $tela->color_id)
                ->value('nombre');

            $extraTela = trim(
                ($tela->ancho ?? '') . ' ' .
                ($tela->largo ?? '') . ' ' .
                ($color ?? '')
            );
        }

        $nombre = trim(
            $producto->codigo . ' ' .
            $producto->nombre . ' ' .
            $producto->marca . ' ' .
            $extraTela
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CATEGORÍA 4 (VESTIMENTA: TALLA + COLOR)
    |--------------------------------------------------------------------------
    */
    elseif ($categoria == 4) {

        $vestimenta = DB::table('vestimentas')
            ->where('producto_id', $id)
            ->first();

        $talla = '';
        $color = '';

        if ($vestimenta) {

            $talla = DB::table('tallas')
                ->where('id', $vestimenta->talla_id)
                ->value('nombre');

            $color = DB::table('colores')
                ->where('id', $vestimenta->color_id)
                ->value('nombre');
        }

        $nombre = trim(
            $producto->codigo . ' ' .
            $producto->nombre . ' ' .
            $producto->marca . ' ' .
            ($talla ?? '') . ' ' .
            ($color ?? '')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CATEGORÍA 5 (ZAPATOS: TALLA ZAPATO + COLOR)
    |--------------------------------------------------------------------------
    */
    elseif ($categoria == 5) {

        $zapato = DB::table('zapatos')
            ->where('producto_id', $id)
            ->first();

        $tallaZapato = '';
        $color       = '';

        if ($zapato) {

            $tallaZapato = DB::table('tallazapatos')
                ->where('id', $zapato->talla_id)
                ->value('nombre');

            $color = DB::table('colores')
                ->where('id', $zapato->color_id)
                ->value('nombre');
        }

        $nombre = trim(
            $producto->codigo . ' ' .
            $producto->nombre . ' ' .
            $producto->marca . ' ' .
            ($tallaZapato ?? '') . ' ' .
            ($color ?? '')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SALDO (TODAS LAS CATEGORÍAS)
    |--------------------------------------------------------------------------
    */
    $saldo = DB::table('lotes')
        ->where('id', $id)
        ->where('saldo', '>', 0)
        ->sum('saldo');

    // Retorno final
    return [
        'nombre' => $nombre,
        'saldo'  => $saldo
    ];
}
     protected static function booted()
    {
        // Siempre excluir registros eliminados (deleted_at no nulo)
        static::addGlobalScope('not_deleted', function ($query) {
            $query->whereNull('deleted_at');
        });
    }
}

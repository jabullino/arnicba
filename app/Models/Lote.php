<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lote extends Model
{
    use SoftDeletes;

    protected $fillable=['codigo','fec_ingre','cantidad','fec_venc','precio','saldo','producto_id','origenfondos'];
    public $timestamps = true;

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    public function origenfondo(): BelongsTo
    {
        return $this->belongsTo(OrigenFondos::class);
    }

     protected static function booted()
    {
        // Siempre excluir registros eliminados (deleted_at no nulo)
        static::addGlobalScope('not_deleted', function ($query) {
            $query->whereNull('deleted_at');
        });
    }
}

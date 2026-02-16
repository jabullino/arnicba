<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EgresoDetalle extends Model
{
   protected $fillable = [
        'egreso_id',
        'producto_id',
        'cantidad'
    ];

    public function egreso()
    {
        return $this->belongsTo(Egreso::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}

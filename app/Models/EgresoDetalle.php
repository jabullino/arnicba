<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EgresoDetalle extends Model
{
   protected $fillable = [
        'egreso_id',
        'producto_id',
        'cantidad',
    ];

    public function egreso()
    {
        return $this->belongsTo(Egreso::class);
    }

    public function producto()
{
    return $this->belongsTo(\App\Models\Producto::class, 'producto_id');
}

    public function detalles()
    {
        return $this->hasMany(EgresoDetalle::class);
    }

    
}

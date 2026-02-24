<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;



class Unidad extends Model
{
     protected $fillable=['nombre'];
     public $table='unidades';
     public $timestamps=false;

    public function Producto(): BelongsToMany
    {
        return $this->belongsToMany(Producto::class);
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

  
}

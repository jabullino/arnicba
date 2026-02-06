<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Capacidad extends Model
{
      protected $fillable=['nombre'];
     public $table='capacidades';
     public $timestamps=false;

     public function producto(): BelongsToMany
    {
        return $this->belongsToMany(Producto::class);
    }

  
}

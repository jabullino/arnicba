<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Presentacion extends Model
{
     protected $fillable=['nombre'];
     public $table='presentaciones';
     public $timestamps=false;

      public function Producto(): BelongsToMany
    {
        return $this->belongsToMany(Producto::class);
    }

     
   
   protected static function booted()
    {
       
    // Siempre excluir registros eliminados (deleted_at no nulo)
        static::addGlobalScope('not_deleted', function ($query) {
            $query->whereNull('deleted_at');
        });
    }
}

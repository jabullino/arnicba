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

   protected static function booted()
    {
       // Siempre excluir registros eliminados (deleted_at no nulo)
        static::addGlobalScope('not_deleted', function ($query) {
            $query->whereNull('deleted_at');
        });
    }
}

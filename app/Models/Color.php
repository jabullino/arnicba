<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
   protected $fillable=['nombre'];
   protected $table='colores';
   public $timestamps=false;

   protected static function booted()
    {
       // Siempre excluir registros eliminados (deleted_at no nulo)
        static::addGlobalScope('not_deleted', function ($query) {
            $query->whereNull('deleted_at');
        });
    }
}

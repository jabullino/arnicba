<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Color extends Model
{
     use SoftDeletes;
   protected $fillable=['nombre'];
   protected $table='colores';
   public $timestamps=false;

   public function productos(): BelongsToMany
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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Egreso extends Model
{
   use SoftDeletes;
   protected $fillable=['fec_egre','cantidad','precio'];

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

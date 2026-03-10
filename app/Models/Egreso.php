<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Egreso extends Model
{
   use SoftDeletes;
   protected $fillable=['fecha','destinatario_id'];

   public function productos(): BelongsToMany
    {
        return $this->belongsToMany(Producto::class);
    }

     public function destinatario(): BelongsTo
    {
        return $this->belongsTo(Destinatario::class);
    }
    public function detalles()
{
    return $this->hasMany(EgresoDetalle::class);
}
public function egresoDetalles()
{
    return $this->hasMany(\App\Models\EgresoDetalle::class, 'egreso_id');
}

}

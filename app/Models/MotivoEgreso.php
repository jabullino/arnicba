<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;


class MotivoEgreso extends Model
{
    protected $fillable=['id','nombre'];
    protected $table='motivo_egresos';

    public function egresoresidentes(): HasMany
    {
        return $this->hasMany(EgresoResidente::class);
    }

    public function devuelveNombre($id){

        $motivo=MotivoEgreso::where('id',$id)->value('nombre');
        return $motivo;
    }
    
}

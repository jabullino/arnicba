<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoCambioCompra extends Model
{
    /** @use HasFactory<\Database\Factories\TipoCambioCompraFactory> */
    use HasFactory;

    protected $fillable=['id','tc'];

    public function asientos(): HasMany
    {

        return $this->hasMany(Asiento::class);

    }

    public function getTipoCambioCompra($id){

        return $tc=TipoCambioCompra::where('id',$id)->value('tc');
    }

   
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoCambioVenta extends Model
{
    /** @use HasFactory<\Database\Factories\TipoCambioVentaFactory> */
    use HasFactory;

    protected $fillable=['id','tv'];

    public function asientos(): HasMany
    {

        return $this->hasMany(Asiento::class);

    }

    public function getTipocambioventa($id){

        return $tv=TipoCambioVenta::where('id',$id)->value('tv');
    }

   
}

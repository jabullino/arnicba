<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoMovimiento extends Model
{
    /** @use HasFactory<\Database\Factories\TipoMovimientoFactory> */
    protected $fillable=['id','nombre'];
    public $timestamps=false;
    use HasFactory;
    
    public function asientos(): HasMany
    {

        return $this->hasMany(Asiento::class);

    }

    public function getTipomovimento($id){

        return $cuenta=TipoMovimiento::where('id',$id)->value('nombre');
    }

    
}

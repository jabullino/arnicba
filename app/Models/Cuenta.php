<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cuenta extends Model
{
    use HasFactory;
    protected $fillable=['nombre'];

    public function asientos(): HasMany
    {
        return $this->hasMany(Asiento::class);
    }

    public function subcuentas(): HasMany
    {
        return $this->hasMany(SubCuenta::class);
    }

    public function getCuenta($id){

        return $cuenta=Cuenta::where('id',$id)->value('nombre');
    }

    public function gastoscajachica(): HasMany
    {
        return $this->hasMany(GastosCajaChica::class);
    }

    

}

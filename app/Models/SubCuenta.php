<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class SubCuenta extends Model
{
    use HasFactory;

    protected $fillable=['nombre','cuenta_id'];

    public function asientos(): HasMany
    {
        return $this->hasMany(Asiento::class);
    }

    public function cuentas(): BelongsTo
    {
        return $this->belongsTo(Cuenta::class);
    }

    public function getSubcuenta($id){

        return $subcuenta=SubCuenta::where('id',$id)->value('nombre');
    }

    public function gastoscajachica(): HasMany
    {
        return $this->hasMany(GastosCajaChica::class);
    }

   
}

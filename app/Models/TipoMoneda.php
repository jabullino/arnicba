<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoMoneda extends Model
{
    /** @use HasFactory<\Database\Factories\TipoMonedaFactory> */
    protected $fillable=['id','nombre'];
    protected $table='tipo_monedas';
    public $timestamps=false;
    use HasFactory;

    public function cuentabancos(): HasMany
    {

        return $this->hasMany(CuentaBancos::class);

    }

   
}

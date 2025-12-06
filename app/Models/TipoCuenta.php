<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoCuenta extends Model
{
    /** @use HasFactory<\Database\Factories\TipoCuentaFactory> */
    protected $fillable=['id','nombre'];
    protected $table='tipo_cuentas';
    public $timestamps=false;
    use HasFactory;

    public function cuentabancos(): HasMany
    {

        return $this->hasMany(CuentaBancos::class);

    }

   
}

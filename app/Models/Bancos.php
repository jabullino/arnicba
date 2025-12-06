<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bancos extends Model
{
    /** @use HasFactory<\Database\Factories\BancosFactory> */
    
    protected $fillable =['id','nombre'];
    public $timestamps=false;
    use HasFactory;

    public function cuentas(): HasMany
    {
        return $this->hasMany(CuentaBancos::class);
    }
    
   
}

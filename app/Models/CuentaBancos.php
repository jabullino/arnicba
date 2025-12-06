<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CuentaBancos extends Model
{
    protected $fillable=['id','banco_id','tipocuenta_id','tipomoneda_id','numcuenta'];
    protected $table='cuenta_bancos';
    public $timestamps=false;
    /** @use HasFactory<\Database\Factories\CuentaBancosFactory> */
    use HasFactory,SoftDeletes;

    public function banco(): BelongsTo
    {
        return $this->belongsTo(Bancos::class);
    }
    
    public function tipocuenta(): BelongsTo
    {
        return $this->belongsTo(TipoCuenta::class);
    }
    
    public function tipomoneda(): BelongsTo
    {
        return $this->belongsTo(TipoMoneda::class);
    }

    protected static function booted()
    {
        // Siempre excluir registros eliminados (deleted_at no nulo)
        static::addGlobalScope('not_deleted', function ($query) {
            $query->whereNull('deleted_at');
        });
    }
}

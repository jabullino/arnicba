<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\GastosCajaChica;

class EntregasCajaChica extends Model
{
    /** @use HasFactory<\Database\Factories\EntregasCajaChicaFactory> */
    protected $fillable=['cajachica_id','fecha_entrega','mes','monto','saldo'];
    protected $table='entregas_caja_chicas';
    use HasFactory,SoftDeletes;

     public function gastoscajachica(): HasMany
    {
        return $this->hasMany(GastosCajaChica::class);
    }

    public function cajachica(): BelongsTo
    {
        return $this->belongsTo(CajaChica::class);
    }

    

    protected static function booted()
    {
        // Siempre excluir registros eliminados (deleted_at no nulo)
        static::addGlobalScope('not_deleted', function ($query) {
            $query->whereNull('deleted_at');
        });
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\EntregasCajaChica;


class GastosCajaChica extends Model
{
    /** @use HasFactory<\Database\Factories\GastosCajaChicaFactory> */
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'entregas_id',
        'cuenta_id',
        'subcuenta_id',
        'fecha_doc',
        'fecha_registro',
        'factura',
        'recibo',
        'importe',
        'status',
    ];
    protected $table = 'gastos_caja_chicas';


    public function entrega(): BelongsTo
    {
        return $this->belongsTo(EntregasCajaChica::class, 'entregas_id');
    }

    public function cuenta(): BelongsTo
    {
        return $this->belongsTo(Cuenta::class);
    }

    public function subcuenta(): BelongsTo
    {
        return $this->belongsTo(SubCuenta::class);
    }

    protected static function booted()
    {
        // Siempre excluir registros eliminados (deleted_at no nulo)
        static::addGlobalScope('not_deleted', function ($query) {
            $query->whereNull('deleted_at');
        });
    }
}

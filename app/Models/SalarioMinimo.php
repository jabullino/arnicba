<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Testing\Constraints\SoftDeletedInDatabase;

class SalarioMinimo extends Model
{
    /** @use HasFactory<\Database\Factories\SalarioMinimoFactory> */
    protected $fillable=['id','gestion_id','mes_inicio','mes_fin','monto'];
    use HasFactory,SoftDeletes;

    function gestion(): BelongsTo
    {
        return $this->belongsTo(Gestion::class);
    }
    
    function estado(): BelongsTo
    {
        return $this->belongsTo(Estado::class);
    }

    protected static function booted()
    {
        // Siempre excluir registros eliminados (deleted_at no nulo)
        static::addGlobalScope('not_deleted', function ($query) {
            $query->whereNull('deleted_at');
        });
    }
}

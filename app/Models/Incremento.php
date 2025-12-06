<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Incremento extends Model
{
    /** @use HasFactory<\Database\Factories\IncrementoFactory> */
    use HasFactory;

    function gestion(): BelongsTo
    {
        return $this->belongsTo(Gestion::class);
    }

    protected static function booted()
    {
        // Siempre excluir registros eliminados (deleted_at no nulo)
        static::addGlobalScope('not_deleted', function ($query) {
            $query->whereNull('deleted_at');
        });
    }
}

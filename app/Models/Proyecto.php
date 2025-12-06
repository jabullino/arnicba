<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Proyecto extends Model
{
    /** @use HasFactory<\Database\Factories\ProyectoFactory> */
    use HasFactory;
    
    protected $fillable=['id','nombre'];

    public function asientos(): HasMany
    {

        return $this->hasMany(Asiento::class);

    }
    
    public function desembolsos(): HasMany
    {

        return $this->hasMany(Desembolso::class);

    }
  
    public function gestion(): BelongsTo

    {

        return $this->belongsTo(Gestion::class);

    }

    public function estado(): BelongsTo
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

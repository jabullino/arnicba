<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Producto extends Model
{
    use SoftDeletes;
    protected $fillable=['nombre','marca','codigo','lineas'];

     public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function vestimenta()
    {
        return $this->hasOne(Vestimenta::class);
    }
    
     public function tela()
    {
        return $this->hasOne(Tela::class);
    }

    public function zapato()
    {
        return $this->hasOne(Zapato::class);
    }

    public function lotes(): HasMany
    {
        return $this->hasMany(Lote::class);
    }

     public function presentacion(): BelongsToMany
    {
        return $this->belongsToMany(Presentacion::class);
    }
    
     public function unidad(): BelongsToMany
    {
        return $this->belongsToMany(Unidad::class);
    }

     public function capacidad(): BelongsToMany
    {
        return $this->belongsToMany(Capacidad::class);
    }

    public function egresos(): BelongsToMany
    {
        return $this->belongsToMany(Egreso::class);
    }

     protected static function booted()
    {
        // Siempre excluir registros eliminados (deleted_at no nulo)
        static::addGlobalScope('not_deleted', function ($query) {
            $query->whereNull('deleted_at');
        });
    }
}

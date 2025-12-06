<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CajaChica extends Model
{
    use SoftDeletes;

    protected $fillable = ['gestion_id'];

    public function gestion()
    {
        return $this->belongsTo(Gestion::class);
    }

     public function entregascajachica(): HasMany
    {
        return $this->hasMany(EntregasCajaChica::class);
    }

    protected static function booted()
    {
        // Siempre excluir registros eliminados (deleted_at no nulo)
        static::addGlobalScope('not_deleted', function ($query) {
            $query->whereNull('deleted_at');
        });
    }
}

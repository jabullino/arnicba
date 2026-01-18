<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HaberBasico extends Model
{
    protected $fillable = ['gestion_id', 'cargo_id', 'monto'];
    /** @use HasFactory<\Database\Factories\HaberBasicoFactory> */
    use HasFactory,SoftDeletes;

     public function gestion()
    {
        return $this->belongsTo(Gestion::class);
    }

    public function cargo()
    {
        return $this->belongsTo(Cargo::class);
    }

    protected static function booted()
    {
        // Siempre excluir registros eliminados (deleted_at no nulo)
        static::addGlobalScope('not_deleted', function ($query) {
            $query->whereNull('deleted_at');
        });
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonoAntiguedad extends Model
{
    /** @use HasFactory<\Database\Factories\BonoAntiguedadFactory> */
    protected $fillable=['id','anios','monto'];
    protected $table='bono_antiguedades';
    public $timestamps=false;
    use HasFactory;
    protected static function booted()
    {
        // Siempre excluir registros eliminados (deleted_at no nulo)
        static::addGlobalScope('not_deleted', function ($query) {
            $query->whereNull('deleted_at');
        });
    }
}

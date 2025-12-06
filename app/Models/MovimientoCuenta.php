<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MovimientoCuenta extends Model
{
    /** @use HasFactory<\Database\Factories\MovimientoCuentaFactory> */
    use HasFactory,SoftDeletes;
    protected $fillable=['id','bancoid','fecha','hora','nombre','descripcion','debito','credito','saldo'];

   protected static function booted()
    {
        // Siempre excluir registros eliminados (deleted_at no nulo)
        static::addGlobalScope('not_deleted', function ($query) {
            $query->whereNull('deleted_at');
        });
    }
}

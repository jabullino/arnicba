<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class TallaZapato extends Model
{
    use SoftDeletes;
    protected $fillable=['nombre'];
    protected $table='tallazapatos';
    public $timestamps=false;

    protected static function booted()
    {
       // Siempre excluir registros eliminados (deleted_at no nulo)
        static::addGlobalScope('not_deleted', function ($query) {
            $query->whereNull('deleted_at');
        });
    }
}

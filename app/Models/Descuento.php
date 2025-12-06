<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Descuento extends Model
{
    /** @use HasFactory<\Database\Factories\DescuentoFactory> */
    protected $fillable=['id','nombre','monto','porcentaje'];
    public $timestamps=false;
    use HasFactory,SoftDeletes;

    public function sueldos(): BelongsToMany
    {
        return $this->belongsToMany(Sueldo::class);

    }

    protected static function booted()
    {
        // Siempre excluir registros eliminados (deleted_at no nulo)
        static::addGlobalScope('not_deleted', function ($query) {
            $query->whereNull('deleted_at');
        });
    }
}

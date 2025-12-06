<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Estado extends Model
{
    /** @use HasFactory<\Database\Factories\EstadoFactory> */
    protected $fillable=['id','nombre','estado_id'];
    use HasFactory;

    public function gestiones(): HasMany

    {

        return $this->hasMany(Gestion::class);

    }

    public function Asientos(): HasMany
    {

        return $this->hasMany(Asiento::class);

    }

    public function proyectos(): HasMany
    {

        return $this->hasMany(Proyecto::class);

    }

    public function salariominimo(): HasMany
    {

        return $this->hasMany(SalarioMinimo::class);

    }

   protected static function booted()
    {
        // Siempre excluir registros eliminados (deleted_at no nulo)
        static::addGlobalScope('not_deleted', function ($query) {
            $query->whereNull('deleted_at');
        });
    }
   
}

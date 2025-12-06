<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bono extends Model
{
    protected $fillable=['id','nombre','monto','porcentaje'];
    public $timestamps=false;
    /** @use HasFactory<\Database\Factories\BonoFactory> */
    use HasFactory;

   public function sueldos()
{
    return $this->belongsToMany(Sueldo::class, 'bono_sueldo')
                ->withPivot('user_id')
                ->withTimestamps();
}

public function users()
{
    return $this->belongsToMany(User::class, 'bono_sueldo')
                ->withPivot('sueldo_id')
                ->withTimestamps();
}

protected static function booted()
    {
        // Siempre excluir registros eliminados (deleted_at no nulo)
        static::addGlobalScope('not_deleted', function ($query) {
            $query->whereNull('deleted_at');
        });
    }

}

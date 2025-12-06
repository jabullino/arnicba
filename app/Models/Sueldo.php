<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Testing\Constraints\SoftDeletedInDatabase;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Sueldo extends Model
{
    /** @use HasFactory<\Database\Factories\SueldoFactory> */
    protected $fillable=['id','mes','total','gestion_id','user_id'];
    use HasFactory,SoftDeletes;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function gestion(): BelongsTo
    {
        return $this->belongsTo(Gestion::class);
    }


    public function bonos()
{
    return $this->belongsToMany(Bono::class, 'bono_sueldo')
                ->withPivot('user_id')
                ->withTimestamps();
}

public function users()
{
    return $this->belongsToMany(User::class, 'bono_sueldo')
                ->withPivot('bono_id')
                ->withTimestamps();
}


    public function descuentos(): BelongsToMany
    {
        return $this->belongsToMany(Descuento::class);
    }

    protected static function booted()
    {
        // Siempre excluir registros eliminados (deleted_at no nulo)
        static::addGlobalScope('not_deleted', function ($query) {
            $query->whereNull('deleted_at');
        });
    }
}

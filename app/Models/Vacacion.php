<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Vacacion extends Model
{
    protected $fillable=['id','user_id','gestion_id','file_id','cant_dias','saldo_dias_gestion','estado_id'];
    protected $table='vacaciones';
    public $timestamps=false;

    /** @use HasFactory<\Database\Factories\VacacionFactory> */
    use HasFactory,SoftDeletes;

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }

    public function diastomadosvaciones(): HasMany
    {
        return $this->hasMany(Diastomadosvacacion::class);
    }

    function gestion(): BelongsTo
    {
        return $this->belongsTo(Gestion::class);
    }

    function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        // Siempre excluir registros eliminados (deleted_at no nulo)
        static::addGlobalScope('not_deleted', function ($query) {
            $query->whereNull('deleted_at');
        });
    }

}

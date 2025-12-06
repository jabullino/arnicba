<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    /** @use HasFactory<\Database\Factories\FileFactory> */
    use HasFactory,SoftDeletes;
    protected $fillable=['codigo','usuario_id'];

    public function user(): BelongsTo
    {

        return $this->belongsTo(User::class);

    }

    public function documentos(): BelongsToMany
    {

        return $this->belongsToMany(Documento::class);

    }

    public function vacaciones(): HasMany
    {
        return $this->hasMany(Vacacion::class);
    }

    public function memorandums(): HasMany
    {
        return $this->hasMany(Memorandum::class);
    }

    public function diastomadosvaciones(): HasMany
    {
        return $this->hasMany(Diastomadosvacacion::class);
    }

    protected static function booted()
    {
        // Siempre excluir registros eliminados (deleted_at no nulo)
        static::addGlobalScope('not_deleted', function ($query) {
            $query->whereNull('deleted_at');
        });
    }
}

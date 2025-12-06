<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Personal extends Model
{
     protected $fillable=['user_id','user_cod'];
     protected $table='personal';
    /** @use HasFactory<\Database\Factories\PersonalFactory> */
    use HasFactory,SoftDeletes;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

     public function documentos(): BelongsToMany
    {
        return $this->belongsToMany(Documento::class);

    }

    protected static function booted()
    {
        // Siempre excluir registros eliminados (deleted_at no nulo)
        static::addGlobalScope('not_deleted', function ($query) {
            $query->whereNull('deleted_at');
        });
    }
}

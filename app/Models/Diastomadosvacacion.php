<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Diastomadosvacacion extends Model
{
    /** @use HasFactory<\Database\Factories\DiastomadosvacacionFactory> */
    protected $fillable=['id','vacacion_id','user_id','file_id','gestion_id','fecsolicitud','fecinicio','fecfin','cantdias'];
    protected $table='diastomadosvacaciones';
    public $timestamps=true;
    use HasFactory,SoftDeletes;
    
    public function vacacion(): BelongsTo
    {
        return $this->belongsTo(Vacacion::class);
    }

     public function gestion(): BelongsTo
    {
        return $this->belongsTo(Gestion::class);
    }

     public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

     public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }
    
    protected static function booted()
    {
        // Siempre excluir registros eliminados (deleted_at no nulo)
        static::addGlobalScope('not_deleted', function ($query) {
            $query->whereNull('deleted_at');
        });
    }
}

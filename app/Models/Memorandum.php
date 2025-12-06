<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Memorandum extends Model
{
    /** @use HasFactory<\Database\Factories\MemorandumFactory> */
    protected $fillable=['id','file_id','codigo','fecha','emisor','destinatario','topico','contenido'];
    protected $table='memorandums';
    use HasFactory,SoftDeletes;

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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Desembolso extends Model
{
    protected $fillable=['id','proyecto_id','fec_desembolso','monto_desembolso'];
    /** @use HasFactory<\Database\Factories\DesembolsoFactory> */
    use HasFactory;

    public function proyecto(): BelongsTo

    {

        return $this->proyectos(Proyecto::class);

    }

    protected static function booted()
    {
        // Siempre excluir registros eliminados (deleted_at no nulo)
        static::addGlobalScope('not_deleted', function ($query) {
            $query->whereNull('deleted_at');
        });
    }
}

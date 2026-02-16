<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Destinatario extends Model
{
    use SoftDeletes;
    protected $fillable=['nombre'];
    public $timestamps = false;

    public function egresos(): HasMany
    {
        return $this->hasMany(Egreso::class);
    }

    protected static function booted()
    {
       // Siempre excluir registros eliminados (deleted_at no nulo)
        static::addGlobalScope('not_deleted', function ($query) {
            $query->whereNull('deleted_at');
        });
    }
}

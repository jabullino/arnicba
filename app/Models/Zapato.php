<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Zapato extends Model
{
    use HasFactory;
    protected $fillable=['producto_id','color_id','talla_id'];
    public $timestamps=true;

     public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

     protected static function booted()
    {
        // Siempre excluir registros eliminados (deleted_at no nulo)
        static::addGlobalScope('not_deleted', function ($query) {
            $query->whereNull('deleted_at');
        });
    }
}

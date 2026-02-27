<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Historial extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'titulo',
        'residente_id',
        'contenido',
    ];

    protected $table = 'historiales';
    public $timestamps=true;

    public function residente(): BelongsTo
    {
        return $this->belongsTo(Residente::class);
    }
}

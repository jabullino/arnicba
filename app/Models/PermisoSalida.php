<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PermisoSalida extends Model
{
    protected $fillable=['gestion_id','cargo_id','user_id','num_permiso','institucion','motivo','fecha_solicitud','fecha_salida','hora_salida','hora_retorno','destino','observaciones','estado','aprobado'];
    protected $table='permiso_salidas';
    public $timestamps=true;
    use SoftDeletes;

    public function gestion(): BelongsTo
    {
        return $this->belongsTo(Gestion::class);
    }

    protected $casts = [
        'aprobado' => 'boolean',
    ];
}

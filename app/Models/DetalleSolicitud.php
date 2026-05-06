<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetalleSolicitud extends Model
{
    use SoftDeletes;

    protected $table = 'detalle_solicitudes';

    protected $fillable = [
        'solicitud_caja_chica_id',
        'descripcion',
        'cantidad',
        
    ];

    public function solicitud()
    {
        return $this->belongsTo(SolicitudCajaChica::class);
    }
}
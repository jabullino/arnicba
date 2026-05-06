<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\Models\DetalleSolicitud; // ✅ CORREGIDO

class SolicitudCajaChica extends Model
{
    use SoftDeletes;

    protected $table = 'solicitud_caja_chicas';

    protected $fillable = [
        'gestion_id',
        'codigo',
        'fecha',
        'estado'
    ];

    public function detalles()
    {
        return $this->hasMany(DetalleSolicitud::class);
    }

    public function gestion()
    {
        return $this->belongsTo(Gestion::class);
    }

    public function getFechaFormateadaAttribute()
{
    return Carbon::parse($this->fecha)->format('d-m-Y');
}
}
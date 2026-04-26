<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;


class EgresoResidente extends Model
{
    use  SoftDeletes;
    protected $fillable=['residente_id','gestion_id','motivo_id','destino','municipio_id','fecha','numjuzgado','numdoc','nomjuez'];
    protected $table='egreso_residentes'; 
    public $timestamps=true;
   
    public function residente(): BelongsTo
    {
        return $this->belongsTo(Residente::class);
    }

    public function gestion(): BelongsTo
    {
        return $this->belongsTo(Gestion::class);
    }

    public function motivoegreso(): BelongsTo
    {
        return $this->belongsTo(MotivoEgreso::class);
    }

    
}

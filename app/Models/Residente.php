<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Extension;
use App\Models\EgresoResidente;
use Carbon\Carbon;



class Residente extends Model
{
    use SoftDeletes;
    
    protected $fillable=['nombre','apellido','fecnac','ci','ext','ciudad','provincia','fec_ingreso','fec_egreso','foto'];
     protected $casts = [
    'fecnac' => 'date',
];
   
    public function acogida(): HasOne
    {
        return $this->hasOne(AcogidaCircunstancial::class);
    }
     public function derivacion(): HasOne
    {
        return $this->hasOne(Derivacion::class);
    }
     public function extension(): BelongsTo
    {
        return $this->belongsTo(Extension::class);
    }
     public function tipologia(): BelongsTo
    {
        return $this->belongsTo(Tipologia::class);
    }
     public function ciudad(): BelongsTo
    {
        return $this->belongsTo(Ciudad::class);
    }

    function provincia(): BelongsTo
    {
        return $this->belongsTo(Provincia::class);
    }

    public function historial(): HasOne
    {
        return $this->hasOne(Historial::class);
    }

    public function derivacionResidente()
    {
      return $this->hasOne(Derivacion::class, 'residente_id');
    }

   public function egreso(): HasOne
    {
        return $this->hasOne(EgresoResidente::class);
    }   

    
}

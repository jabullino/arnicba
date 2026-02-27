<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AcogidaCircunstancial extends Model
{
    use SoftDeletes;
    protected $fillable=['residente_id','fecha','numdoc','ciudad','municipio','tipologia','firmante'];
    protected $table='acogida_circunstanciales';

    protected $casts = [
    'fecnac' => 'date',
];

    public function residente(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
   
    function provincia(): BelongsTo
    {
        return $this->belongsTo(Provincia::class);
    }
    

    public function tipologiaRel(): BelongsTo
{
    return $this->belongsTo(Tipologia::class, 'tipologia');
}

public function ciudadRel(): BelongsTo
{
    return $this->belongsTo(Ciudad::class, 'ciudad');
}

public function municipioRel(): BelongsTo
{
    return $this->belongsTo(Municipio::class, 'municipio');
}

    
}

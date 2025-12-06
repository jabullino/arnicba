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

    public function residente(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function ciudad(): BelongsTo
    {
        return $this->belongsTo(Ciudad::class);
    }

    function municipio(): BelongsTo
    {
        return $this->belongsTo(Municipio::class);
    }

    function provincia(): BelongsTo
    {
        return $this->belongsTo(Provincia::class);
    }
    function tipologia(): BelongsTo
    {
        return $this->belongsTo(Tipologia::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Derivacion extends Model
{
    use SoftDeletes;

    protected $fillable=['residente_id','fecha','numjuzgado','numdoc','nomjuez'];
    protected $table='derivaciones';

    public function residente(): BelongsTo
    {
        return $this->belongsTo(Residente::class);
    }
}

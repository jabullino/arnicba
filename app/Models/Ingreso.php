<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\OrigenFondos;

class Ingreso extends Model
{
    use SoftDeletes;
    protected $fillable = ['fecha', 'factura', 'recibo', 'origen_id'];
    public $timestamps = true;

    public function origen()
{
    return $this->belongsTo(OrigenFondos::class, 'origen_id');
}

    public function detalles()
    {
        return $this->hasMany(DetalleIngreso::class);
    }
}

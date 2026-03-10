<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Ingreso;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class DetalleIngreso extends Model
{
    use SoftDeletes;
    protected $fillable=['cantidad','producto_id','precio','ingreso_id','vencimiento'];
    public $timestamps=true;

    public function ingreso(): BelongsTo
    {
        return $this->belongsTo(Ingreso::class);
    }
public function producto()
{
    return $this->belongsTo(\App\Models\Producto::class, 'producto_id');
}



   

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Municipio extends Model
{
    /** @use HasFactory<\Database\Factories\MunicipioFactory> */
    use HasFactory,SoftDeletes;
    protected $fillable=['ciudad_id','nombre'];
    public $timestamps=false;

    public function ciudad(): BelongsTo
    {
        return $this->belongsTo(Ciudad::class);
    }

    public function acogidas(): HasMany
    {
        return $this->hasMany(AcogidaCircunstancial::class);
    }

    public function derivaciones(): HasMany
    {
        return $this->hasMany(Derivacion::class);
    }

    public function devuelveNombre($id){
        $nombre=Municipio::where('id',$id)->value('nombre');
        return $nombre;
    } 
}

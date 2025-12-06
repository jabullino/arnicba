<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Grado extends Model
{
    use SoftDeletes;
    protected $fillable=['nombre'];
    public $timestamps=false;

     public function residentes(): BelongsToMany
    {
        return $this->belongsToMany(Residente::class);

    }

     public function unidadeducativas(): BelongsToMany
    {
        return $this->belongsToMany(UnidadEducativa::class);
    }

     public function cursos(): BelongsToMany
    {
        return $this->belongsToMany(Curso::class);
    }
     public function gestiones(): BelongsToMany
    {
        return $this->belongsToMany(Gestion::class);
    }
}

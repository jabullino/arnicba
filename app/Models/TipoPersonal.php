<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;



class TipoPersonal extends Model
{
   

    protected $fillable=['id','nombre'];
    protected $table='tipo_personales';
    public $timestamps=false;

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function personal(): HasMany
    {
        return $this->hasMany(Personal::class);
    }
}

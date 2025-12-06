<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cargo extends Model
{

    use HasFactory,SoftDeletes;

    protected $fillable=['id','nombre'];
    public $timestamps=false;

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function getCargo ($idCargo){
      
        return $nomCargo=Cargo::where('id',$idCargo)->value('nombre');

    }

    public function devuelveNombreCargoAdministrador(){

        return Cargo::where('id','4')->value('nombre');
    }

    public function devuelveNombreCargoDirector(){

        return Cargo::where('id','2')->value('nombre');
    }


    public function haberesBasicos()
    {
        return $this->hasMany(HaberBasico::class);
    }

    protected static function booted()
    {
        // Siempre excluir registros eliminados (deleted_at no nulo)
        static::addGlobalScope('not_deleted', function ($query) {
            $query->whereNull('deleted_at');
        });
    }

    
}

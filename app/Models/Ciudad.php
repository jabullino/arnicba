<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ciudad extends Model
{
    use HasFactory;
    protected $fillable=(['id','nombre']);
    protected $table='ciudades';
    public $timestamps=false;


    public function provincias(): HasMany
    {
        return $this->hasMany(Provincia::class);
    }

    public function municipios(): HasMany
    {
        return $this->hasMany(Municipio::class);
    }
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
   
    public function residentes(): HasMany
    {
        return $this->hasMany(Residente::class);
    }

    public function acogidas(): HasMany
    {
        return $this->hasMany(AcogidaCircunstancial::class);
    }


    public function getCiudad ($idCiudad){
      
        return $nomCiudad=Ciudad::where('id',$idCiudad)->value('nombre');

    }

   protected static function booted()
    {
        // Siempre excluir registros eliminados (deleted_at no nulo)
        static::addGlobalScope('not_deleted', function ($query) {
            $query->whereNull('deleted_at');
        });
    }    

}

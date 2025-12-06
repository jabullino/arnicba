<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Provincia extends Model
{
    use HasFactory;

    protected $fillable=['id','nombre'];
    public $timestamps=false;

    function ciudades(): BelongsTo
    {
        return $this->belongsTo(Ciudad::class);
    }

    public function residentes(): HasMany
    {
        return $this->hasMany(Residente::class);
    }

    public function acogidas(): HasMany
    {
        return $this->hasMany(AcogidaCircunstancial::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function getProvincia ($idProvincia){
      
        return $nomCargo=Provincia::where('id',$idProvincia)->value('nombre');

    }

    protected static function booted()
    {
        // Siempre excluir registros eliminados (deleted_at no nulo)
        static::addGlobalScope('not_deleted', function ($query) {
            $query->whereNull('deleted_at');
        });
    }
}

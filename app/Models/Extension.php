<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Residente;

class Extension extends Model
{
    use HasFactory;
    protected $fillable=(['id','nombre']);
    protected $table='extensiones';
    public $timestamps=false;

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function residentes(): HasMany
    {
        return $this->hasMany(Residente::class);
    }

    public function getExtension ($idExtension){
      
        return $nomExtension=Extension::where('id',$idExtension)->value('nombre');

    }

    protected static function booted()
    {
        // Siempre excluir registros eliminados (deleted_at no nulo)
        static::addGlobalScope('not_deleted', function ($query) {
            $query->whereNull('deleted_at');
        });
    }
}

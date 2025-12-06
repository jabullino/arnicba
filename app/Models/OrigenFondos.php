<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrigenFondos extends Model
{
    /** @use HasFactory<\Database\Factories\OrigenFondosFactory> */
   
    protected $fillable=['id','nombre'];
    public $timestamps=false;
   
    use HasFactory;

    public function asientos(): HasMany
    {

        return $this->hasMany(Asiento::class);

    }

    public function getOrigenfondos($id){

        return $origenfondos=OrigenFondos::where('id',$id)->value('nombre');
    }

    
}

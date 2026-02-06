<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Categoria extends Model
{
    use HasFactory;
    protected $fillable=['nombre'];
    public $timestamps = false;

     public function productos(): HasMany
    {
        return $this->hasMany(Producto::class);
    }

    
}

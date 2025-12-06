<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tipologia extends Model
{
    /** @use HasFactory<\Database\Factories\TipologiaFactory> */
    use HasFactory;
    protected $fillable=['nombre'];
    public $timestamps=false;

    public function acogida(): HasMany
    {
        return $this->hasMany(AcogidaCircunstancial::class);
    }
     public function residente(): HasMany
    {
        return $this->hasMany(Residente::class);
    }
    
}

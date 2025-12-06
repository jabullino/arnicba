<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\File;

class Documento extends Model
{
    /** @use HasFactory<\Database\Factories\DocumentoFactory> */
    use HasFactory,SoftDeletes;
    
    protected $fillable=['id','nombre'];
    public $timestamps=false;

    public function file(): BelongsToMany
    {
        return $this->belongsToMany(File::class);

    }


    protected function nombre():Attribute
    {
           return Attribute::make(
           
            get: fn (string $value) => ucfirst($value),
            set: fn (string $value) => ucfirst($value),

           );

    }

    protected static function booted()
    {
        // Siempre excluir registros eliminados (deleted_at no nulo)
        static::addGlobalScope('not_deleted', function ($query) {
            $query->whereNull('deleted_at');
        });
    }

}

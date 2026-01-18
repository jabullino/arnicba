<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Testing\Constraints\SoftDeletedInDatabase;
use App\Models\Gestion;
use Carbon\Carbon;

class SalarioMinimo extends Model
{
    /** @use HasFactory<\Database\Factories\SalarioMinimoFactory> */
    protected $fillable=['id','gestion_id','monto'];
    use HasFactory,SoftDeletes;

    function gestion(): BelongsTo
    {
        return $this->belongsTo(Gestion::class);
    }
    
    function estado(): BelongsTo
    {
        return $this->belongsTo(Estado::class);
    }

    public static function CreaSalarioMinimo($salmin){
  
       $smnId = SalarioMinimo::orderBy('id', 'desc')->first();
       $mesActual = Carbon::now()->format('m');
       $ultimaGestion = Gestion::orderBy('id', 'desc')->first();
       $ultimoIdSmn=SalarioMinimo::orderBy('id', 'desc')->first();

      
         
         SalarioMinimo::create([
            'gestion_id'=>$ultimaGestion,
            'monto'=>$salmin,
         ]);
        
         
      
         
       
    }

    protected static function booted()
    {
        // Siempre excluir registros eliminados (deleted_at no nulo)
        static::addGlobalScope('not_deleted', function ($query) {
            $query->whereNull('deleted_at');
        });
    }
}

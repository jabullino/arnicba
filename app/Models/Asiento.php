<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Cuenta;
use App\Models\SubCuenta;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class Asiento extends Model
{
    protected $fillable=['gestion_id','fec_asiento','tc_id','tv_id','recibo','factura','cuenta','sub_cuenta','monto_bs','monto_sus','origenfondos_id','tipomovimento_id','proyecto_id','estado_id'];
    /** @use HasFactory<\Database\Factories\AsientoFactory> */
    use HasFactory,SoftDeletes;
    

    public function gestion(): BelongsTo
    {

        return $this->belongsTo(Gestion::class);

    }

    public function tipocambiocompra(): BelongsTo
    {

        return $this->belongsTo(TipoCambioCompra::class);

    }

    public function tipocambioventa(): BelongsTo
    {

        return $this->belongsTo(TipoCambioVenta::class);

    }
    public function cuenta(): BelongsTo
    {

        return $this->belongsTo(Cuenta::class);

    }
    public function subcuenta(): BelongsTo
    {

        return $this->belongsTo(SubCuenta::class);

    }

    public function tipomovimiento(): BelongsTo
    {

        return $this->belongsTo(TipoMovimiento::class);

    }

    public function OrigenFondos(): BelongsTo
    {

        return $this->belongsTo(OrigenFondos::class);

    }

    public function proyecto(): BelongsTo
    {

        return $this->belongsTo(Proyecto::class);

    }

    public function estado(): BelongsTo
    {

        return $this->belongsTo(Estado::class);

    }

    public function getCuenta($id){

        return $nomCuenta=Cuenta::where('id',$id)->value('nombre');
    }

    public function getSubcuenta($id){

        return $nomCuenta=SubCuenta::where('cuenta_id',$id)->value('nombre');
    }

    protected function fec_asiento(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Carbon::parse($value)->format('d-m-Y'),
                   
        );
    }

    public function verificaId($id){
        
        if (Session::has('anteriorId')) {
            $valorSesion = session('anteriorId');
            if($valorSesion!=$id){
            Session::put('anteriorId', $id);
               return 1;
            }
            else{
              return 0;
            }
        } else {
            Session::put('anteriorId', $id);
            return 1;
        }
   }

   public function sumabs($montobs){
    
     return session(['montobs' => session('montobs', 0) + $montobs]);
      
   }

   public function sumasus($montosus){
    
    return session(['montosus' => session('montosus', 0) + $montosus]);
     
  }

  protected static function booted()
    {
        // Siempre excluir registros eliminados (deleted_at no nulo)
        static::addGlobalScope('not_deleted', function ($query) {
            $query->whereNull('deleted_at');
        });
    }
}

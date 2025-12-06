<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Models\Vacacion;
use App\Models\Gestion;
use App\Models\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class VacacionController extends Controller
{

    public function index(){

        return view('Administrador.FormAsignaVacaciones');
    }
    public function asignaVacacion(Request $request){
         
       $validator = Validator::make($request->all(), [
         'fecha' => 'required|date|before_or_equal:today',

      ],);

      if ($validator->fails()) {
         return redirect()->back()->withErrors($validator)->withInput();
      }
          try{
            DB::beginTransaction();
         $fechaFormulario = Carbon::parse($request->fecha);
         $gestion=Carbon::parse($fechaFormulario)->format('Y');
         $gestionId=Gestion::where('nombre',$gestion)->value('id');
        $usuarios=[];
        $usuarios=User::where('id', '!=', 1)
             ->select('id', 'fec_ingreso')
             ->get();

        foreach($usuarios as $usr){
          
            $fileId=File::where('usuario_id',$usr->id)->value('id');
            $dias=$this->calculaDias($fechaFormulario,$usr->fec_ingreso);
          $dias=(int)$dias;
           $vacaciones= Vacacion::create([
            'user_id'=>$usr->id,
            'gestion_id' => $gestionId,
            'file_id' => $fileId,
            'cant_dias' => $dias,
            'saldo_dias_gestion' => $dias,
            'estado_id'=>'1',

         ]);

        }
       session()->flash('success', '¡Se Asignaron las vacaciones exitosamente!');
       DB::commit();
       return redirect()->route('asignavacaciones');
       }catch (QueryException $e) {
            DB::rollBack();
            return back()->with('error', 'No se pudieron asignar las vacaciones' . $e->getMessage());
        }
    }

    public function calculaDias($fecha,$fec_ingreso){
          
         $fechaFormulario = Carbon::parse($fecha);
         $fecha_ingreso = Carbon::parse($fec_ingreso);
         
      $diff = $fechaFormulario->diff($fecha_ingreso);
      $anios = $diff->y;
      $meses = $diff->m;
       
      if($anios>=1 && $anios<5){
        return (int)15;
      }elseif($anios>=5 && $anios<10){
        return (int)20;
      }elseif($anios>=10){
        return (int)30;
      }elseif($anios<1 && $meses >0){
         return(float)(($meses/12)*15);
      }     
      
    }
}

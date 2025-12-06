<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Documento;
use App\Models\Cargo;
use App\Models\File;
use App\Models\HaberBasico;
use App\Models\Personal;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class RegistraPersonalController extends Controller
{
    public function index()
    {
               $usuarios = User::where('status', 'pendiente')
               ->where('id', '!=', 1)
               ->get();
                $cont=1;
                if ($usuarios->isEmpty()) {
                     session()->flash('error', '¡No existen usuarios pendientes para ser registrados!');
                    return view('Administrador.FormCreaPersonal');
                 }else{
                    return view('Administrador.FormCreaPersonal')->with(['usuarios'=>$usuarios,'cont'=>$cont]);
                 } 

               
    }

    public function preparapersonal(string $usrId){
       
        $usr = User::find($usrId);
        $nom = substr($usr->nombre, 0, 3);
        $ape = substr($usr->apellido, 0, 3);
        $ddmm = \Carbon\Carbon::parse($usr->fecnac)->format('d-m');
        $codpersonal = $nom.$ape.'-'.$ddmm;
        $documentos = Documento::orderBy('nombre', 'asc')->get();
        $cargo=Cargo::where('id',$usr->cargo_id)->value('nombre');
        $cargo_id=User::where('id',$usrId)->value('cargo_id');
        $haberbasico=HaberBasico::where('cargo_id', $cargo_id)
                ->orderBy('id', 'desc') // o por created_at si lo tienes
                ->value('monto');
        return view('Administrador.FormVerificaDatosPersonal')->with(['idusuario'=>$usr->id,'codpersonal'=>$codpersonal,'nombre'=>$usr->nombre,'apellido'=>$usr->apellido,'cargo'=>$cargo,'haberbasico'=>$haberbasico,'documentos'=>$documentos]);


    }

    public function registrapersonal(Request $request){
      try{
        DB::beginTransaction();
         $file=File::create([
             'usuario_id' => $request->codigousuario,
             'codigo' => $request->codigo,

         ]);


         $personal=Personal::create([
             'user_id' => $request->codigousuario,
             'user_cod' => $request->codigo,

         ]);

         User::where('id', $request->codigousuario)
              ->update(['status' => 'registrado']);
        
         $file->documentos()->attach($request->input('documentos', []));
         session()->flash('success', '¡Personal Creado exitosamente!');
         DB::commit();
         return redirect()->route('PersonalPendiente');
        }catch (QueryException $e) {
            DB::rollBack();
            return back()->with('error', 'No se registrar al personal solicitado' . $e->getMessage());
        } 
    }

}

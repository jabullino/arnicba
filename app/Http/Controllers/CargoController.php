<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cargo;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class CargoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cargos=Cargo::all();
        $cont=0;
        return view ('AdminSis.Cargos')->with(['cargos'=>$cargos,'cont'=>$cont]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('AdminSis.CreaCargo');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
          
        $validator = Validator::make($request->all(), [
            'cargo' => 'required', 'regex:/^[\pL\s\.\(\)]+$/u', 
            'haberbasico'=>'required|decimal',   
        ]);

        // Si la validación falla, redirige con los errores
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try{
            DB::beginTransaction();
        Cargo::create([
            'nombre' => $request->cargo,
            'haberbasico'=>$request->haberbasico,
            
        ]);
        DB::commit();
        session()->flash('success', '¡Cargo creado exitosamente!');
        $cargos=Cargo::all();
        $cont=0;
          return view('Adminsis.Cargos')->with(['cont'=>$cont,'cargos'=>$cargos]);
       }catch(QueryException $e){
           DB::rollBack();
            return back()->with('error', 'No pudo crear el cargo' . $e->getMessage());
       } 
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $cargo=Cargo::find($id);
        return view('AdminSis.EditaCargo')->with(['cargo'=>$cargo]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        
        $validator = Validator::make($request->all(), [
            'cargo' => 'required', 'regex:/^[\pL\s\.\(\)]+$/u', 
            'haberbasico'=>'required','regex:/^\d{1,10}(\.\d{1,2})?$/',  
                  
        ]);

        // Si la validación falla, redirige con los errores
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
          try{
            DB::beginTransaction();
            $cargo =Cargo::find($id);
            $cargo->nombre = $request->cargo;
            $cargo->haberbasico=$request->haberbasico;
            $cargo->save();
            DB::commit();
            $cargos=Cargo::all();
            session()->flash('success', '¡Cargo Editado exitosamente!');
            return redirect()->route('Cargos.index')->with(['cargos',$cargos]);
         }catch(QueryException $e){
             DB::rollBack();
            return back()->with('error', 'No se pudo editar el cargo' . $e->getMessage());
         }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
        DB::beginTransaction();
        $cargo=Cargo::find($id);
        $cargo->delete();
         $cargos=Cargo::all();
        session()->flash('success', '¡Cargo eliminado exitosamente!');
         DB::commit();
        return redirect()->route('Cargos.index')->with(['success','Cargo Eliminado Exitosamente','documentos'=>$cargos]);
        
        }catch(QueryException $e){
             DB::rollBack();
            return back()->with('error', 'No se pudo eliminar el cargo' . $e->getMessage());
         }
       
    }

    
}

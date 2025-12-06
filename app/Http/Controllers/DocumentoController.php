<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Documento;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class DocumentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $documentos=Documento::all();
        $cont=0;
        return view ('AdminSis.Documentos')->with(['documentos'=>$documentos,'cont'=>$cont]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       return view('AdminSis.CreaDocumento');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         
        $validator = Validator::make($request->all(), [
            'documento' => 'required|alpha_spaces|max:105',    
        ]);

        // Si la validación falla, redirige con los errores
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try{
        DB::beginTransaction();
        Documento::create([
            'nombre' => $request->documento,
            
        ]);
        session()->flash('success', '¡Documento creado exitosamente!');
        $documentos=Documento::all();
        $cont=0;
         DB::commit();
        return view('Adminsis.Documentos')->with(['documentos'=>$documentos,'cont'=>$cont,'cargos'=>$documentos]);
       
        }catch (QueryException $e) {
            DB::rollBack();
            return back()->with('error', 'No pudo crear el documento' . $e->getMessage());
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
        
        $documento=Documento::find($id);
        return view('AdminSis.EditaDocumento')->with(['documento'=>$documento]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    { 
        
        $validator = Validator::make($request->all(), [
            'documento' => 'required|alpha_spaces|max:255',
                  
        ]);

        // Si la validación falla, redirige con los errores
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
           try{
            DB::beginTransaction();
            $documento =Documento::find($id);
            $documento->nombre = $request->documento;
            $documento->save();
            DB::commit();
            $documentos=Documento::all();
            session()->flash('success', '¡Documento Editado exitosamente!');
            return redirect()->route('Documentos.index')->with(['documentos',$documentos]);
           }catch (QueryException $e) {
            DB::rollBack();
            return back()->with('error', 'No se pudo editar el documento' . $e->getMessage());
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
        DB::beginTransaction();
        $documento=Documento::find($id);
        $documento->delete();
        DB::commit();
        $documentos=Documento::all();
        session()->flash('success', '¡Documento Eliminado exitosamente!');
        return redirect()->route('Documentos.index')->with(['success','Documento Eliminado Exitosamente','documentos'=>$documentos]);
       }catch (QueryException $e) {
            DB::rollBack();
            return back()->with('error', 'No se pudo eliminar el documento' . $e->getMessage());
        } 
    }
}

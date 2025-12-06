<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CuentaBancos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Bancos;
use App\Models\TipoCuenta;
use App\Models\TipoMoneda;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class CuentaBancoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banco=Bancos::all();   
        $tipocuenta=TipoCuenta::all();
        $tipomoneda=TipoMoneda::all();
        return view('Administrador.FormCreaCuentaBanco')->with(['banco'=>$banco,'tipocuenta'=>$tipocuenta,'tipomoneda'=>$tipomoneda]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         $banco=Bancos::all();   
        $tipocuenta=TipoCuenta::all();
        $tipomoneda=TipoMoneda::all();
         return view('Administrador.FormCreaCuentaBanco')->with(['banco'=>$banco,'tipocuenta'=>$tipocuenta,'tipomoneda'=>$tipomoneda]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $cuenta = CuentaBancos::where('numcuenta', $request->numcuenta)->first();

if ($cuenta) {
    return back()->withErrors(['error' => 'El número de cuenta ya está registrado.']);
}
      
        $validator = Validator::make($request->all(), [
            'banco' => 'required',
            'tipocuenta' => 'required', 
            'tipomoneda' => 'required',  
            'numcuenta' => 'required|numeric', 
        ]);

        // Si la validación falla, redirige con los erroresp
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try{
            DB::beginTransaction();
         CuentaBancos::create([
            'banco_id' => $request->banco,
            'tipocuenta_id'=>$request->tipocuenta,
            'tipomoneda_id'=>$request->tipomoneda,
            'numcuenta'=>$request->numcuenta,
            
        ]);
        session()->flash('success', '¡Cuenta creada exitosamente!');
        DB::commit(); 
        $banco=Bancos::all();   
        $tipocuenta=TipoCuenta::all();
        $tipomoneda=TipoMoneda::all();
        
        return view('Administrador.FormCreaCuentaBanco')->with(['banco'=>$banco,'tipocuenta'=>$tipocuenta,'tipomoneda'=>$tipomoneda]);
             
    }catch (QueryException $e) {
            DB::rollBack();
            return back()->with('error', 'No se pudo crear el Banco' . $e->getMessage());
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

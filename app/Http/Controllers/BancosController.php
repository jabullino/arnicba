<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Bancos;
use App\Models\TipoCuenta;
use App\Models\TipoMoneda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BancosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bancos = Bancos::all();
        $tipocuenta=TipoCuenta::all();
        $tipomoneda=TipoMoneda::all();
        return view('Administrador.FormCreaCuentaBanco')->with(['bancos'=>$bancos,'tipocuenta'=>$tipocuenta,'tipomoneda'=>$tipomoneda]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $bancos = Bancos::all();
         $tipocuenta=TipoCuenta::all();
        $tipomoneda=TipoMoneda::all();
        return view('Administrador.FormCreaCuentaBanco')->with(['bancos'=>$bancos,'tipocuenta'=>$tipocuenta,'tipomoneda'=>$tipomoneda]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        

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

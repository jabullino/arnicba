<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CuentaBancos;

class ObtenCuentasController extends Controller
{
    public function obtenerCuentas($bancoId)
    {
        
        $cuentas = CuentaBancos::where('banco_id', $bancoId)->get(['id', 'numcuenta']);
        return response()->json($cuentas);
    }
}

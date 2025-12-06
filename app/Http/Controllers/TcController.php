<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TipoCambioCompra;

class TcController extends Controller
{
    public function checkTc(Request $request)
    {
        // Obtenemos el valor enviado
        //$valor = $request->input('tc');
        $valor = $request->valor;
        // Verificamos si el valor existe en la base de datos
        $existe = TipoCambioCompra::where('tc', $valor)->exists();

        if ($existe) {
            return response()->json(['exists' => true,'valor'=>$valor]);
        } else {
            // Si no existe, insertamos el valor en la base de datos
            $nuevoTc = TipoCambioCompra::create([
                'tc' => $valor,
            ]);

            return response()->json(['exists' => false, 'valor' => $valor]);
        }
    }
}

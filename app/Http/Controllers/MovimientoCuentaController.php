<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MovimientoCuenta;
use Illuminate\Support\Facades\DB;
use App\Models\Bancos;
use App\Models\CuentaBancos;
use Carbon\Carbon;
use Illuminate\Database\QueryException;



class MovimientoCuentaController extends Controller
{

    public function index()
    {

        $banco = Bancos::all();
        return view('Administrador.FormCargaArchivoCVS')->with(['banco' => $banco]);
    }

    public function importar(Request $request)
    {

        $bancoid = CuentaBancos::where('banco_id', $request->banco)->value('id');
        $request->validate([
            'archivo' => 'required|file|mimes:csv,txt',
        ]);
        try{
        $path = $request->file('archivo')->getRealPath();
        $file = fopen($path, 'r');
        
            DB::beginTransaction();
        if (($handle = fopen($path, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                MovimientoCuenta::create([
                    'bancoid'     => $bancoid,
                    $fecha = str_replace('/', '-', $row[0]),
                    $fechaConvertida = Carbon::createFromFormat('d-m-Y', $fecha)->format('Y-m-d'),
                    'fecha'        => $fechaConvertida,
                    'hora'         => $row[1],
                    'nombre'       => $row[7],
                    'descripcion'  => $row[9],
                    'debito'       => ($row[18] === null || $row[18] === '') ? 0 : $row[18],
                    $row[18],
                    'credito'      => ($row[19] === null || $row[19] === '') ? 0 : $row[19],
                    $row[19],
                    'saldo'        => ($row[20] === null || $row[20] === '') ? 0 : $row[20],
                    $row[20],
                ]);
            }
            fclose($handle);
            session()->flash('success', '¡Archivo importado exitosamente!');
            DB::commit();
            return redirect()->back()->with('success', 'Archivo importado correctamente.');
            
        }
        }catch (QueryException $e) {
            DB::rollBack();
            return back()->with('error', 'No se pudo importar el archivo' . $e->getMessage());
        }


    }
}

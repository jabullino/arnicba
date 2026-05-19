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

    try {

        DB::beginTransaction();

        $path = $request->file('archivo')->getRealPath();

        if (($handle = fopen($path, 'r')) !== false) {

            while (($row = fgetcsv($handle, 1000, ',')) !== false) {

                // Validar cantidad mínima de columnas
                if (count($row) < 21) {
                    continue;
                }

                // Limpiar fecha
                $fechaRaw = $row[0] ?? '';

                $fechaRaw = str_replace("\0", '', $fechaRaw);
                $fechaRaw = trim($fechaRaw);
                $fechaRaw = str_replace('/', '-', $fechaRaw);

                // Validar fecha vacía
                if ($fechaRaw === '') {
                    continue;
                }

                try {

                    $fechaConvertida = Carbon::createFromFormat(
                        'd-m-Y',
                        $fechaRaw
                    )->format('Y-m-d');

                } catch (\Exception $e) {

                    \Log::error('Fecha inválida', [
                        'fecha' => $fechaRaw,
                        'row' => $row
                    ]);

                    continue;
                }

                MovimientoCuenta::create([

                    'bancoid'     => $bancoid,
                    'fecha'       => $fechaConvertida,
                    'hora'        => $row[1] ?? null,
                    'nombre'      => $row[7] ?? null,
                    'descripcion' => $row[9] ?? null,

                    'debito' => empty($row[18])
                        ? 0
                        : str_replace(',', '', $row[18]),

                    'credito' => empty($row[19])
                        ? 0
                        : str_replace(',', '', $row[19]),

                    'saldo' => empty($row[20])
                        ? 0
                        : str_replace(',', '', $row[20]),
                ]);
            }

            fclose($handle);
        }

        DB::commit();

        return redirect()->back()
            ->with('success', 'Archivo importado correctamente.');

    } catch (\Exception $e) {

        DB::rollBack();

        return back()->with(
            'error',
            'No se pudo importar el archivo: ' . $e->getMessage()
        );
    }
}
}

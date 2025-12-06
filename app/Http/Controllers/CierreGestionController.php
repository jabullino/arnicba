<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Gestion;
use App\Models\Asiento;
use App\Models\Vacacion;
use App\Models\Diastomadosvacacion;
use App\Models\HaberBasico;
use App\Models\SalarioMinimo;
use App\Models\Sueldo;
use App\Models\MovimientoCuenta;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\SoftDeletes;


class CierreGestionController extends Controller
{

    public function index()
    {
        $gestionesEliminadas = DB::table('gestiones')->whereNotNull('deleted_at')->get();

        return view('AdminSis.FormReabreGestion')->with(['gestiones' => $gestionesEliminadas]);
    }

    public function cierraGestion(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'gestion' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with(['error' => 'Debe escoger una gestion']);
        } else {

            try {
                DB::beginTransaction();
                $nombreGestion = Gestion::where('id', $request->gestion)->value('nombre');

                Asiento::where('gestion_id', $request->gestion)
                    ->update(['deleted_at' => now()]);

                Vacacion::where('gestion_id', $request->gestion)
                    ->update(['deleted_at' => now()]);

                Diastomadosvacacion::where('gestion_id', $request->gestion)
                    ->update(['deleted_at' => now()]);

                HaberBasico::where('gestion_id', $request->gestion)
                    ->update(['deleted_at' => now()]);

                DB::table('movimiento_cuentas')
                    ->whereBetween('fecha', [
                        $nombreGestion . '-01-01',
                        $nombreGestion . '-12-31'
                    ])
                    ->delete();

                SalarioMinimo::where('gestion_id', $request->gestion)
                    ->update(['deleted_at' => now()]);

                Sueldo::where('gestion_id', $request->gestion)
                    ->update(['deleted_at' => now()]);

                Gestion::where('id', $request->gestion)
                    ->update(['deleted_at' => now()]);
                DB::commit();
                session()->flash('success', "Gestión cerrada existosamente");
                return redirect()->route('Gestiones.index');
            } catch (QueryException $e) {
                DB::rollBack();
                return back()->with('error', 'No se pudo cerrar la gestión:' . $e->getMessage());
            }
        }
    }

   public function reabreGestion(Request $request)
{
    try {
        DB::beginTransaction();

        $nombreGestion = Gestion::where('id', $request->gestion)->value('nombre');

        DB::table('asientos')
            ->where('gestion_id', $request->gestion)
            ->whereNotNull('deleted_at')
            ->update(['deleted_at' => null]);

        DB::table('vacaciones')
            ->where('gestion_id', $request->gestion)
            ->whereNotNull('deleted_at')
            ->update(['deleted_at' => null]);

        DB::table('diastomadosvacaciones')
            ->where('gestion_id', $request->gestion)
            ->whereNotNull('deleted_at')
            ->update(['deleted_at' => null]);

        DB::table('haber_basicos')
            ->where('gestion_id', $request->gestion)
            ->whereNotNull('deleted_at')
            ->update(['deleted_at' => null]);

        DB::table('salario_minimos') // nombre corregido
            ->where('gestion_id', $request->gestion)
            ->whereNotNull('deleted_at')
            ->update(['deleted_at' => null]);

        DB::table('sueldos')
            ->where('gestion_id', $request->gestion)
            ->whereNotNull('deleted_at')
            ->update(['deleted_at' => null]);

        DB::table('gestiones')
            ->where('id', $request->gestion)
            ->whereNotNull('deleted_at')
            ->update(['deleted_at' => null]);

        DB::table('movimiento_cuentas')
            ->whereBetween('fecha', [$nombreGestion . '-01-01', $nombreGestion . '-12-31'])
            ->whereNotNull('deleted_at')
            ->update(['deleted_at' => null]);

        DB::commit();

        session()->flash('success', "Gestión reabierta existosamente");
        return redirect()->route('FormReabreGestion');

    } catch (QueryException $e) {
        DB::rollBack();
        return back()->with('error', 'No se pudo reabrir la gestión: ' . $e->getMessage());
    }
}



}

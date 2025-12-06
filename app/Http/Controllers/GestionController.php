<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Gestion;
use App\Models\Asiento;
use App\Models\Vacacion;
use App\Models\Diastomadosvacacion;
use App\Models\EntregasCajaChica;
use App\Models\HaberBasico;
use App\Models\MovimientoCuenta;
use App\Models\SalarioMinimo;
use App\Models\Sueldo;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;


class GestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $gestiones = Gestion::whereNull('deleted_at')->get();

        return view('AdminSis.FormCierraGestion')->with(['gestiones' => $gestiones]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
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
                    ->update(['deleted_at' => null]);

                Vacacion::where('gestion_id', $request->gestion)
                    ->update(['deleted_at' => null]);

                Diastomadosvacacion::where('gestion_id', $request->gestion)
                    ->update(['deleted_at' => null]);

                HaberBasico::where('gestion_id', $request->gestion)
                    ->update(['deleted_at' => null]);

                DB::table('movimiento_cuentas')
                    ->whereBetween('fecha', [
                        $nombreGestion . '-01-01',
                        $nombreGestion . '-12-31'
                    ])
                    ->delete();

                SalarioMinimo::where('gestion_id', $request->gestion)
                    ->update(['deleted_at' => null]);

                Sueldo::where('gestion_id', $request->gestion)
                    ->update(['deleted_at' => null]);

                Gestion::where('id', $request->gestion)
                    ->update(['deleted_at' => null]);
                 DB::commit(); 
                session()->flash('success', "Gestión cerrada existosamente");
                return redirect()->route('Gestiones');
            } catch (QueryException $e) {
                 DB::rollBack();
                return back()->with('error', 'No se pudo cerrar la gestión:' . $e->getMessage());
            }
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

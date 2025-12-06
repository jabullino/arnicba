<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Cargo;
use App\Models\Personal;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\File;

class PersonalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $usuarios = DB::table('users')
            ->join('cargos', 'users.cargo_id', '=', 'cargos.id')
            ->select(
                'users.id',
                'users.nombre',
                'users.apellido',
                'users.ci',
                DB::raw("DATE_FORMAT(users.fec_ingreso, '%d-%m-%Y') as fec_ingreso"),
                'cargos.nombre as cargo',
                /*  'personal.user_cod',
                DB::raw("(SELECT monto 
                  FROM haber_basicos 
                  WHERE cargo_id = users.cargo_id 
                  ORDER BY id DESC 
                  LIMIT 1) as ultimo_monto")*/
            )
            ->where('users.id', '!=', 1)
            ->get();



        $cont = 1;
        return view('AdminSis.FormGestionaPersonal')->with(['usuarios' => $usuarios, 'cont' => $cont]);
    }

    public function formulariogestionusuarios()
    {

        $persId = Personal::where('deleted_at', null)
            ->where('user_id', '!=', 1)
            ->select('id', 'user_cod')
            ->get();
        foreach ($persId as $persona) {
            $usuarios = User::where('id', $persona->user_id)
                ->get();
        }
        $cont = 1;
        return view('AdminSis.FormGestionPersonal')->with(['usuario' => $usuarios, 'cont' => $cont]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store($id)
    {

        $nombreinicial = User::where('id', $id)->value('nombre');
        $nombrefinal = substr($nombreinicial, 0, 3);
        $apellidoinicial = User::where('id', $id)->value('apellido');
        $apellidofinal = substr($apellidoinicial, 0, 3);
        $fechanac = User::where('id', $id)->value('fecnac');
        $fecnac = Carbon::parse($fechanac)->format('d-m');
        $codigo = $nombrefinal.$apellidofinal .'-'. $fecnac;

        try {
            Personal::create([
                'user_id' => $id,
                'user_cod' => $codigo,
            ]);

            
        } catch (\Exception $e) {
            return back()->with('error', 'Error al crear el registro: ' . $e->getMessage());
        }

        try {
            File::create([
                'codigo' => $codigo,
                'usuario_id' => $id,
            ]);

            return back()->with('success', 'Registro creado correctamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al crear el registro: ' . $e->getMessage());
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


    public function edit($id)
    {
        $usuario = DB::table('users')
            ->join('personal', 'users.id', '=', 'personal.user_id')
            ->join('cargos', 'users.cargo_id', '=', 'cargos.id')
            ->select(
                'users.id',
                'users.nombre',
                'users.apellido',
                'users.ci',
                DB::raw("DATE_FORMAT(users.fec_ingreso, '%Y-%m-%d') as fec_ingreso"),
                'users.cargo_id',
                'cargos.nombre as cargo_nombre',
                'personal.user_cod',
                // Subquery para obtener el último monto de haber_basicos según cargo_id
                DB::raw("(SELECT monto 
                      FROM haber_basicos 
                      WHERE cargo_id = users.cargo_id 
                      ORDER BY id DESC 
                      LIMIT 1) as ultimo_monto")
            )
            ->where('users.id', $id)
            ->first();

        $cargos = DB::table('cargos')->pluck('nombre', 'id');

        return view('AdminSis.FormEditaPersonal', compact('usuario', 'cargos'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validación básica
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'ci' => 'required|string|max:50',
            'fec_ingreso' => 'required|date',
            'cargo_id' => 'required|integer|exists:cargos,id',
            'user_cod' => 'required|string|max:50',
            'ultimo_monto' => 'nullable|numeric|min:0', // validación para el último monto
        ]);

        // Actualizar tabla users
        DB::table('users')->where('id', $id)->update([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'ci' => $request->ci,
            'fec_ingreso' => $request->fec_ingreso,
            'cargo_id' => $request->cargo_id,
        ]);

        // Actualizar tabla personal
        DB::table('personal')->where('user_id', $id)->update([
            'user_cod' => $request->user_cod,
        ]);

        // Actualizar el último monto en haber_basicos
        if ($request->ultimo_monto !== null) {
            // Buscar el último registro para el cargo actual
            $ultimo = DB::table('haber_basicos')
                ->where('cargo_id', $request->cargo_id)
                ->orderByDesc('id')
                ->first();

            if ($ultimo) {
                DB::table('haber_basicos')->where('id', $ultimo->id)->update([
                    'monto' => $request->ultimo_monto
                ]);
            }
            // No se crea nuevo registro, solo se actualiza el existente
        }

        return redirect()->route('Personal')->with('success', 'Usuario actualizado correctamente.');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Comenzamos una transacción para mayor seguridad
        DB::beginTransaction();
        try {
            // Eliminar el registro en tabla personal
            DB::table('personal')->where('user_id', $id)->delete();

            // Eliminar el usuario
            DB::table('users')->where('id', $id)->delete();

            // Commit si todo sale bien
            DB::commit();

            return redirect()->route('Personal')->with('success', 'Usuario eliminado correctamente.');
        } catch (\Exception $e) {
            // Rollback si hay error
            DB::rollBack();

            return redirect()->route('Personal')->with('error', 'Error al eliminar el usuario: ' . $e->getMessage());
        }
    }


    public function altaPersonal(string $id)
    {
        //
    }
}

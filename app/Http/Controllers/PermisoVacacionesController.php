<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Cargo;
use App\Models\Diastomadosvacacion;
use App\Models\Vacacion;
use App\Models\Personal;
use App\Models\File;
use App\Models\Gestion;
use Illuminate\Support\Facades\Validator;

class PermisoVacacionesController extends Controller
{
    public function index()
    {
        $personal = User::where('id', '!=', 1)
            ->select('id', 'nombre', 'apellido', 'cargo_id')
            ->get();
        $cargo = new Cargo;
        $usr = new User;
        $gestion=Gestion::latest('id')->value('nombre');

        return view('Administrador.FormPermisoVacaciones')->with(['personal' => $personal, 'cargo' => $cargo, 'user' => $usr,'gestion'=>$gestion]);
    }

    public function guardaPermisoVacaciones(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|alpha_spaces|max:255',
            'apellido' => 'required|alpha_spaces|max:255',
            'codigo' => 'required|string|max:20',
            'cargo' => 'required',
            'fecingreso' => 'required|date',
            'fecsolicitud' => 'required|date|after_or_equal:today',
            'fecinicio' => 'required|date|after_or_equal:fecsolicitud|before_or_equal:fecfin',
            'fecfin' => 'required|date|after_or_equal:fecinicio|after_or_equal:fecsolicitud',
            'totaldias' => 'required|numeric',


        ]);

        // Si la validación falla, redirige con los errores
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }


        $usuario_id = Personal::where('user_cod', $request->codigo)->value('user_id');
        $file_id = File::where('usuario_id', $usuario_id)->value('id');
        $gestion_id = Gestion::where('nombre', $request->gestion)->value('id');
        $vacacion_id = Vacacion::where('user_id', $usuario_id)
            ->where('gestion_id', $gestion_id)
            ->value('id');
        $diasVacacion = $this->verificaDias($request->totaldias, $usuario_id, $gestion_id);

        if ($diasVacacion != false) {

            $diastomadosvacacion = DIASTOMADOSVACACION::create([

                'vacacion_id' => $vacacion_id,
                'user_id' => $usuario_id,
                'file_id' => $file_id,
                'gestion_id' => $gestion_id,
                'fecsolicitud' => $request->fecsolicitud,
                'fecinicio' => $request->fecinicio,
                'fecfin' => $request->fecfin,
                'cantdias' => $request->totaldias,

            ]);

            Vacacion::where('user_id', $usuario_id)
                ->where('gestion_id', $gestion_id)
                ->update(['saldo_dias_gestion' => $diasVacacion]);

            if ($diasVacacion == 0) {
                $gestion = Gestion::where('id', $gestion_id)->value('nombre');
                Vacacion::where('user_id', $usuario_id)
                    ->where('gestion_id', $gestion_id)
                    ->update(['estado_id' => '2']);
                session()->flash('error', "¡Los dias solicitados de vacacion le fueron autorizados, pero ya no tiene mas dias de vacacion disponibles para la gestion $gestion");
                $personal = User::where('id', '!=', 1)
                    ->select('id', 'nombre', 'apellido', 'cargo_id')
                    ->get();
                $cargo = new Cargo;
                $usr = new User;
                 
            } else {

                session()->flash('success', "¡Los dias solicitados de vacacion le fueron autorizados");
                $personal = User::where('id', '!=', 1)
                    ->select('id', 'nombre', 'apellido', 'cargo_id')
                    ->get();
                $cargo = new Cargo;
                $usr = new User;

                 return redirect()->route('solicitavacaciones')->with(['personal'=>$personal,'cargo'=>$cargo,'user'=>$usr]);
            }
            session()->flash('success', "Solicitud de vacación autorizada");
                return redirect()->route('solicitavacaciones')->with(['success'=>"Solicitud de vacación autorizada",'personal'=>$personal,'cargo'=>$cargo,'user'=>$usr]);
        } else {
            session()->flash('diasInsuficientes', "Ud. no tiene acceso a la cantidad de dias de vacación solicitidos");
            $personal = User::where('id', '!=', 1)
                ->select('id', 'nombre', 'apellido', 'cargo_id')
                ->get();
            $cargo = new Cargo;
            $usr = new User;

            return redirect()->route('solicitavacaciones')->with(['personal'=>$personal,'cargo'=>$cargo,'user'=>$usr]);
        }
    }

    public function verificaDias($diasSolicitados, $usuario_id, $gestion_id)
    {
        $diasDisponibles = Vacacion::where('user_id', $usuario_id)
            ->where('gestion_id', $gestion_id)
            ->value('saldo_dias_gestion');
        if ($diasDisponibles < $diasSolicitados) {
            return false;
        } else {
            $saldoDias = $diasDisponibles - $diasSolicitados;
            return $saldoDias;
        }
    }
}

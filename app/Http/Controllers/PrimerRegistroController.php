<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Extension;
use App\Models\Ciudad;
use App\Models\User;
use App\Models\Cargo;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Database\QueryException;


class PrimerRegistroController extends Controller
{
    public function primerRegistro(){

        $extensiones=Extension::all();
        $ciudades=Ciudad::all();
        return view ('AdminSis.UserAdminSis')->with(['extensiones'=>$extensiones,'ciudades'=>$ciudades]);
    }

    public function registraAdminSis(Request $request){
         
         $validator = Validator::make($request->all(), [
            'nombre' => 'required|alpha_spaces|max:255',
            'apellido' => 'required|alpha_spaces|max:255',
            'ci' => 'required|string|max:20',
            'extension' => 'required',
            'fecnac' => 'required|date',
            'ciudad' => 'required',
            'provincia' => 'required',
            'direccion' => 'required|string|max:100',
            'referencias' => 'required|string|max:100',
            'latitud' => 'numeric',
            'longitud' => 'numeric',
            'telefono' => 'required|string|max:20',
            'email' => 'required|email|max:255|unique:users',

            // Reglas del password
            'password' => [
                'required',
                'confirmed',
                'string',
                'min:8',
                'regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/'
            ],

            'password_confirmation' => 'required|same:password|min:8',
            'cargo' => 'required',
            'fecingreso' => 'required|date',
            'foto' => 'image|mimes:jpeg,png,jpg,gif|max:1000'
        ]);


        // Si la validación falla, redirige con los errores
        if ($validator->fails()) {
            
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            DB::beginTransaction();
            $nom = substr($request->nombre, 0, 3);
            $ape = substr($request->apellido, 0, 3);
            $nomfinal = $nom . $ape;
            if ($request->has('foto')) {
                $file = $request->file('foto');
                $nombrePersonalizado = Str::slug($nomfinal) . '.' . $file->getClientOriginalExtension();
                $ruta = $file->storeAs('fotos', $nombrePersonalizado, 'public');
            } else {
                $ruta = 'Foto no escogida';
            }
           
            $user = User::create([
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'ci' => $request->ci,
                'extension_id' => $request->extension,
                'fecnac' => $request->fecnac,
                'ciudad_id' => $request->ciudad,
                'provincia_id' => $request->provincia,
                'direccion' => $request->direccion,
                'referencias' => $request->referencias,
                'telefono' => $request->telefono,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'cargo_id' => $request->cargo,
                'fec_ingreso' => Carbon::today()->toDateString(),
                'fec_egreso' => Carbon::today()->toDateString(),
                'rutafoto' => $ruta,
                'latitud' => NULL,
                'longitud' => NULL,

            ]);
            $cargos = new Cargo();
            $usuarios = User::all();
            $cont = 0;
            session()->flash('success', '¡Usuario creado exitosamente!');
            DB::commit();
            return view('Adminsis.Usuarios')->with(['usuarios' => $usuarios, 'cont' => $cont, 'cargos' => $cargos]);
        } catch (QueryException $e) {
            DB::rollBack();
            return back()->with('error', 'No se pudo crear el usuario' . $e->getMessage());
        }
    }
}

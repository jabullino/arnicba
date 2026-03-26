<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use GdImage;
use Carbon\Carbon;

class UserAdminSisController extends Controller
{
    public function index($extensiones,$ciudades,$cargos)
    {
        return view('AdminSis.UserAdminSis');
    }//fin funcion index

    public function saveadminsis(Request $request){

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|alpha_spaces|max:255',
            'apellido' => 'required|alpha_spaces|max:255',
            'ci' => 'required|string|max:20|unique:users',
            'extension' => 'required',
            'fecnac' => 'required|date',
            'ciudad' => 'required',
            'provincia' => 'required',
            'direccion' => 'required|string|max:100',
            'referencias' => 'required|string|max:100',
            'telefono' => 'required|string|max:20',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation'=>'required|same:password|min:6|max:20',
            'cargo' => 'required',
            'latitud'  => 'nullable|numeric',
            'longitud' => 'nullable|numeric',
            'status' => 'Activo',
            
        ]);

        // Si la validación falla, redirige con los errores
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
       
          
    User::create([
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
        'fec_ingreso'=>Carbon::today()->toDateString(),
        'fec_egreso'=>Carbon::today()->toDateString(),
        
    ]);
    session()->flash('success', '¡Usuario creado exitosamente!');
      return view('welcome')->with(['UsrAdministrador'=>'Usuario creado exitosamente']);

    }//fin funcion  

   
}//fin clase

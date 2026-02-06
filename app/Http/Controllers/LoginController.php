<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Laravel\Fortify\Fortify;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\PanelAdminSisController;
use App\Http\Controllers\PanelAdministradorController;
use App\Models\Cargo;

class LoginController extends Controller
{
    public function login(Request $request){

       
      try{
       $validate=$request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
     } catch (ValidationException $e) {
        // Si la validación falla, puedes hacer algo específico
        // Puedes obtener los errores con: $e->errors()
        session()->flash('CredencialesNoValidas', 'Sus credenciales son inválidas');
        return redirect()->route('inicio');
    } 
        // Intentar autenticar al usuario
        if (Auth::attempt(['email' => $request->email, 'password'=>$request->password ])) {
            // Obtener el usuario autenticado
            $user = Auth::user();
            $cargo_id=Auth::user()->cargo_id; 
            // Redirigir según el cargo del usuario
            if ($cargo_id == 1) {
                return redirect()->route('PanelAdminSis'); // Redirigir a vista de admin
            }elseif ($cargo_id == 4) {
                return redirect()->route('PanelAdministrador'); // Redirigir a vista de usuario
            }elseif ($cargo_id == 10) {
                return redirect()->route('PanelAlmacen'); // Redirigir a vista de usuario
            }else{ 
                session()->flash('CredencialesNoValidas', 'Sus credenciales no corresponden a un usuario habilitado'); 
                return redirect()->route('inicio');
            }
        }else{
            session()->flash('CredencialesNoValidas', 'Sus credenciales no corresponden a un usuario habilitado'); 
                return redirect()->route('inicio');
        }

        
    }
}

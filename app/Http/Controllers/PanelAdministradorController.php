<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cargo;
use Illuminate\Support\Facades\Auth;

class PanelAdministradorController extends Controller
{
    public function index(){
       
        $nomCargo=new Cargo;
        $cargoUsr=$nomCargo->getCargo(Auth::user()->cargo_id);   
       
        return view('Administrador.PanelAdministrador')->with(['cargoUsr'=>$cargoUsr]);
 
     }
}

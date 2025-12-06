<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cargo;

class PanelAdminSisController extends Controller
{
    public function index(){
       
        $nomCargo=new Cargo;
        $cargoUsr=$nomCargo->getCargo(Auth::user()->cargo_id); 
        return view('AdminSis.PanelAdminSis')->with(['cargoUsr'=>$cargoUsr]);

    }
}

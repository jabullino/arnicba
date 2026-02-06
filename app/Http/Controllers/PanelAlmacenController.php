<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PanelAlmacenController extends Controller
{
    public function index(){

        return view('Almacen.PanelAlmacen');
    }
}

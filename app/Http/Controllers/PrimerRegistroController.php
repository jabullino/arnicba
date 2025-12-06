<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PrimerRegistroController extends Controller
{
    public function primerRegistro(){

        return view ('AdminSis.UserAdminSis');
    }
}

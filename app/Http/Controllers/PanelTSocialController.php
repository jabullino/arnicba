<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PanelTSocialController extends Controller
{
    public function index(){

        return view('TSocial.PanelTSocial');
    }
}

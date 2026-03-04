<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TwoFactorController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\EntregasCajaChicaController;
use App\Models\SubCuenta;
use App\Http\Controllers\GastosCajaChicaController;
use App\Http\Controllers\ConsolidaCajaChicaController;
use App\Http\Controllers\PrimerRegistroController;
use App\Models\Personal;
use App\Models\Cargo;
use Illuminate\Support\Carbon;
USE App\Models\SalarioMinimo;
use App\Models\HaberBasico;
use Illuminate\Support\Facades\Log;
use App\Models\Gestion;
use App\Models\User;
use App\Models\Vacacion;
use App\Models\Municipio;
use App\Http\Controllers\AlumnosController;
use App\Http\Controllers\UserAdminSisController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ImpresionIngresosEgresosController;

Route::post('AdminSis.RegistraAdminSis',[UserAdminSisController::class,'saveadminsis'])->name('AdminSis.RegistraAdminSis');
Route::get('/provincias/{ciudad_id}', function($ciudad_id) {
   $provincias = \App\Models\Provincia::where('ciudad_id', $ciudad_id)->get();
   return response()->json($provincias);
});

Route::get('/subcuentas/{cuenta_id}', function($cuenta_id) {
   $subcuentas = Subcuenta::where('cuenta_id', $cuenta_id)->get();
   return response()->json($subcuentas);
});

Route::get('/municipios/{ciudad_id}', function($ciudad_id) {
   $municipios = Municipio::where('ciudad_id', $ciudad_id)->get();
   return response()->json($municipios);
});

Route::get('/get-municipios/{ciudad_id}', function ($ciudad_id) {
    $municipios = \App\Models\Municipio::where('ciudad_id', $ciudad_id)
        ->select('id', 'nombre')
        ->orderBy('nombre')
        ->get();

    return response()->json($municipios);
})->name('getMunicipios');

// LOGIN / LOGOUT
Route::get('/', [AuthenticatedSessionController::class, 'create'])->name('inicio');
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login');
Route::post('/logout', function(Request $request){
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// CONFIGURACIÓN 2FA
Route::middleware('auth')->group(function(){
    Route::get('/two-factor-setup', [TwoFactorController::class, 'show'])->name('two-factor.setup');
    Route::post('/two-factor-setup', [TwoFactorController::class, 'store'])->name('two-factor.store');

    Route::get('/two-factor-verify', [TwoFactorController::class, 'verify'])->name('two-factor.verify');
    Route::post('/two-factor-verify', [TwoFactorController::class, 'verifyPost'])->name('two-factor.verify.post');
});

// RUTAS PROTEGIDAS (auth + twofactor)
Route::middleware(['auth', 'twofactor'])->group(function(){
    Route::get('/dashboard', fn()=>view('dashboard'))->name('dashboard');
    Route::get('/adminsis/dashboard', fn()=>view('adminsis.dashboard'))->name('PanelAdminSis');
    Route::get('/administrador/dashboard', fn()=>view('administrador.dashboard'))->name('PanelAdministrador');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::fallback(function () {
    return redirect()->route('inicio'); // redirige siempre al login si la ruta no existe
});

//FORGOT PASSWORD 
Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
    ->middleware('guest')
    ->name('password.request');

Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
    ->middleware('guest')
    ->name('password.reset');

Route::post('reset-password', [NewPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.store'); 
Route::get('entregascajachicas/ajax', [EntregasCajaChicaController::class, 'ajax'])
    ->name('entregascajachicas.ajax');

Route::get('/gastos-caja-chica/ajax', [GastosCajaChicaController::class, 'obtenerGastos'])
    ->name('gastoscajachica.ajax');

Route::get('/consolidar-caja-chica/ajax', [ConsolidaCajaChicaController::class, 'obtenerPagos'])
    ->name('pagoscajachica.ajax');

//DATOS DE USUARIO PARA PAGAR SUELDO

Route::get('/datosusuariosueldoindividual/{user_id}', function($user_id) {
    
    try {
        $usuario = User::find($user_id);

        if (!$usuario) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        $codigo = Personal::where('user_id', $user_id)->value('user_cod'); // ⚡ usa solo un campo
        $cargo = Cargo::where('id', $usuario->cargo_id)->value('nombre');
        

        return response()->json([
            'nombre' => $usuario->nombre,
            'apellido' => $usuario->apellido,
            'cargo' => $cargo,
            'codigo' => $codigo,
            
        ]);
    } catch (\Throwable $e) {
        // Así siempre devuelves JSON aunque falle
        return response()->json(['error' => $e->getMessage()], 500);
    }
});


Route::get('/datosusuariosueldonumeros/{mesPago}/{user_id}', function ($mesPago, $user_id) {
    // parseo seguro de la fecha
    try {
        $date = Carbon::parse($mesPago);
    } catch (\Exception $e) {
        Log::error('Fecha inválida en datosusuariosueldonumeros', ['mesPago'=>$mesPago,'error'=>$e->getMessage()]);
        return response()->json(['error'=>'Formato de fecha inválido', 'mesPago'=>$mesPago], 400);
    }

    $mes = $date->format('m');
    $anio = $date->format('Y');
    $gestion_id=Gestion::where('nombre',$anio)->value('id');
    $usuario = User::find($user_id);
    if (!$usuario) {
        return response()->json(['error'=>'Usuario no encontrado','user_id'=>$user_id], 404);
    }

    $cargo_id = $usuario->cargo_id ?? null;
    if (!$cargo_id) {
        return response()->json(['error'=>'Usuario no tiene cargo_id','user_id'=>$user_id], 400);
    }

    if($mes>=01 && $mes <=05){
        $mes_inicio='01';
        $mes_fin='05';
    }else{
        $mes_inicio='06';
        $mes_fin='12';
    }

 //Log::error('los datos antes de haberbasioc son ', ['cargo_id'=>$cargo_id,'gestion'=>$gestion_id,'mes inicio'=>$mes_inicio,'mes_fin'=>$mes_fin]);
    // intento 1: mes_inicio/mes_fin almacenados como enteros (1..12)
    $haberbasico = HaberBasico::where('cargo_id', $cargo_id)
        ->where('gestion_id', $gestion_id)
        ->where('mes_inicio', '=', $mes_inicio)
        ->where('mes_fin', '=', $mes_fin)
        ->value('monto');
     //Log::error('los datos antes de smn son ', ['haberbasico'=>$haberbasico,'gestion'=>$gestion_id,'mes inicio'=>$mes_inicio,'mes_fin'=>$mes_fin]);
    $smn = SalarioMinimo::where('gestion_id', $gestion_id)
        ->where('mes_inicio','=',$mes_inicio)
        ->where('mes_fin','=',$mes_fin)
        ->value('monto');
     // Log::error('el valor de smn es ', ['smn'=>$smn]);
      $fechaAnterior = Carbon::parse($mesPago);
      $fecha_ingreso = Carbon::parse($usuario->fec_ingreso);

      $diff = $fechaAnterior->diff($fecha_ingreso);
      $anios = $diff->y;
      $meses = $diff->m;
      $aniosDecimal = $anios + ($meses / 12);

      if ($aniosDecimal >= 2 && $aniosDecimal < 5) {
         $bonoant = $smn * 0.05;
      } elseif ($aniosDecimal >= 5 && $aniosDecimal < 8) {
         $bonoant = $smn * 0.11;
      } elseif ($aniosDecimal >= 8 && $aniosDecimal < 11) {
         $bonoant = $smn * 0.18;
      } elseif ($aniosDecimal >= 11 && $aniosDecimal < 15) {
         $bonoant = $smn * 0.26;
      } elseif ($aniosDecimal >= 15 && $aniosDecimal < 20) {
         $bonoant = $smn * 0.34;
      } elseif ($aniosDecimal >= 20 && $aniosDecimal < 25) {
         $bonoant = $smn * 0.42;
      } elseif ($aniosDecimal >= 25) {
         $bonoant = $smn * 0.50;
      } else{
          $bonoant=0;
      }
    $bonoantformateado=number_format($bonoant, 2, '.', ',');
    $haberbasicoformateado=number_format($haberbasico, 2, '.', ',');
    $porcentaje_gestora=0.1271;
    $subtotal=$haberbasico+$bonoant;
    $subtotalformateado=number_format($subtotal, 2, '.', ',');
    $descuento_gestora=$subtotal*$porcentaje_gestora;
    $descuentogestoraformateado=number_format($descuento_gestora, 2, '.', ',');
    $total=$subtotal-$descuento_gestora;
    $totalformateado=number_format($total, 2, '.', ',');
    return response()->json([
        'haberbasico'    => $haberbasicoformateado,
        'bonoantiguedad' =>$bonoantformateado,
        'subtotal'=>$subtotalformateado,
        'gestora'=>$descuentogestoraformateado,
        'total'=>$totalformateado,
        
    ]);
});

Route::get('CreaAdminSis',[PrimerRegistroController::class,'primerRegistro'])->name('CreaAdminSis');
Route::get('/datosusuariovacacion/{user_id}', function($user_id) {

   $usuario = User::find($user_id);
    $cargo=Cargo::where('id',$usuario->cargo_id)->value('nombre');
    $codigo=Personal::where('user_id',$user_id)->value('user_cod');
    $vacacion = Vacacion::where('user_id', $user_id)
    ->where('saldo_dias_gestion', '>', 0)
    ->orderBy('id', 'DESC')
    ->first(); // Trae el primer registro más reciente
    $gestionId = $vacacion->gestion_id; // Accedes al campo directamente
    $gestion=Gestion::where('id',$gestionId)->value('nombre');
  
   // $gestionNombre=Gestion::where('id',$gestionId)->value('nombre');    

    if (!$usuario) {
        return response()->json(['error' => 'Usuario no encontrado'], 404);
    }
     $fechaFormateada = Carbon::parse($usuario->fec_ingreso)->format('d-m-Y');
    return response()->json([
        'nombre' => $usuario->nombre,
        'apellido' => $usuario->apellido,
        'fecingreso' => $fechaFormateada,
        'cargo'=>$cargo,
        'codigo'=>$codigo,
        'gestion'=>$gestion,
       
    ]);
});

Route::get('TSocial/escolaridad/alumnos', [AlumnosController::class, 'alumnos'])
     ->name('escolaridad.alumnos'); // <- nombre limpio sin slashes


     //RUTAS DE ALMACEN

     Route::get('/Almacen/buscar-producto', [ProductoController::class, 'buscar'])
     ->name('productos.buscar');

     Route::get(
'/almacen/impresion/ingreso/{id}/{indice?}',
[ImpresionIngresosEgresosController::class,'flujoImpresionIngreso']
)->name('almacen.impresion.ingreso');

Route::get(
'/almacen/impresion/ingreso/imprimir/{id}/{indice}',
[ImpresionIngresosEgresosController::class,'imprimirProductoIngreso']
)->name('almacen.impresion.ingreso.imprimir');
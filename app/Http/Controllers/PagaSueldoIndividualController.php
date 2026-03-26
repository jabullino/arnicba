<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Descuento;
use App\Models\Bono;
use App\Models\User;
use App\Models\Personal;
use App\Models\Sueldo;
use Illuminate\Support\Carbon;
use App\Models\TipoCambioCompra;
use App\Models\TipoCambioVenta;
use App\Models\Asiento;
use App\Models\Gestion;
use Illuminate\Support\Facades\DB;
use App\Models\SalarioMinimo;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;

class PagaSueldoIndividualController extends Controller
{
   /**
    * Display a listing of the resource.
    */
   public function index()
   {
      $descuentos = Descuento::where('id', '!=', '5')->get();
      $bonos = Bono::where('id', '!=', 9)->get();
      $user = User::where('id', '!=', 1)->get();
      return view('Administrador.FormPagaSueldoIndividual')->with(['user' => $user, 'bonos' => $bonos, 'descuentos' => $descuentos]);
   }

   /**
    * Show the form for creating a new resource.
    */
   public function create(Request $request) {}


   /**
    * Store a newly created resource in storage.
    */
   public function store(Request $request)
   {


      $fechapago = Carbon::parse($request->mespago)->format('Y-m-d');

      $userId = $request->idUsuario;

      $validator = Validator::make($request->all(), [
         'mespago' => 'required|date|before_or_equal:today',
         'personal' => 'required',

      ], [
         'mespago.required' => 'Debes seleccionar una fecha.',
         'personal' => 'Debe escoger un usuario para pagar el sueldo',
      ]);

      if ($validator->fails()) {
         return redirect()->back()->withErrors($validator)->withInput();
      }


      $tipocambiocompra_id = TipoCambioCompra::latest('id')->value('id');
      $valortipocambiocompra = TipoCambioCompra::where('id', $tipocambiocompra_id)->value('tc');
      $valortipocambiocompra = str_replace(',', '', $valortipocambiocompra);
      $valortipocambiocompra = (float)$valortipocambiocompra;
      $valortipocambiocompra = round($valortipocambiocompra, 2);
      $tipocambioventa_id = TipoCambioVenta::latest('id')->value('id');


      $fechapago = Carbon::parse($request->mespago);

      $mes = $fechapago->format('m');
      $anio = $fechapago->format('Y');
      $gestion = $anio;
      $gestion_id = Gestion::where('nombre', $gestion)->value('id');
      $cargoId = User::where('id', $userId)->value('cargo_id');

      $mescomparacion = $fechapago->copy()->subMonth()->format('m');

      $verificagestion = Sueldo::where('user_id', $userId)
         ->where('gestion_id', (int)$gestion_id)
         ->where('mes', (int)$mescomparacion)
         ->first();

      if ($verificagestion != null) {
         session()->flash('success', '¡El mes que intenta cancelar para el usuario, ya esta registrado en el sistema!');
         return redirect()->back()->with(['sueldospagados' => 'El mes que intenta pagar ya está registrado']);
      }


      /*---aqui empezaba el foreach */
      $ultimoId = Asiento::latest('id')->value('id');
      $sueldoBs = $request->total;
      $sueldoBs = str_replace(',', '', $sueldoBs);
      $sueldoBs = (float)$sueldoBs;
      $sueldoBs = round($sueldoBs, 2);
      $bonoantiguedad = round($request->bonoantiguedad, 2);
      $sueldototalbs = $sueldoBs + $bonoantiguedad;
      $sueldoSus = $sueldoBs / round($valortipocambiocompra, 2);

      if ($cargoId == 2 || $cargoId == 3) {
         $subcuenta = 11;
      } elseif ($cargoId == 4) {
         $subcuenta = 9;
      } elseif ($cargoId == 5 || $cargoId == 6 || $cargoId == 7) {
         $subcuenta = 12;
      } elseif ($cargoId == 8 || $cargoId == 9) {
         $subcuenta = 10;
      } elseif ($cargoId == 10 || $cargoId == 11) {
         $subcuenta = 13;
      } else {
         $subcuenta = 14;
      }

      //dd($gestion_id,$request->mespago,$tipocambiocompra_id,$tipocambioventa_id,$ultimoId+1,$subcuenta,$sueldoBs,$sueldoSus);
      try {
         DB::beginTransaction();
         $asiento = Asiento::create([
            'gestion_id' => $gestion_id,
            'fec_asiento' => $fechapago,
            'tc_id' => $tipocambiocompra_id,
            'tv_id' => $tipocambioventa_id,
            'recibo' => $ultimoId + 1,
            'factura' => NULL,
            'cuenta' => '3',
            'sub_cuenta' => $subcuenta,
            'monto_bs' => $sueldoBs,
            'monto_sus' => $sueldoSus,
            'origenfondos_id' => '1',
            'tipomovimiento_id' => '1',
            'proyecto_id' => null,
            'estado_id' => 1,
         ]);

         $mes = $mes - 1;
         $sueldo = Sueldo::create([
            'mes' => $mes,
            'total' => $sueldototalbs,
            'gestion_id' => $gestion_id,
            'user_id' => $userId,

         ]);


         $fechadeingreso = User::where('id', $userId)->value('fec_ingreso');
         $antId = $this->obtenIdAntiguedad($request->fechapago, $fechadeingreso);
         $sueldo_id = Sueldo::latest('id')->value('id');
         if ($request->bonoantiguedad != 0) {
            DB::table('bono_sueldo')->insert([
               'bono_id' => '10',
               'sueldo_id' => $sueldo_id,
               'user_id' => $userId, // o el user_id que corresponda
               'monto' => $request->bonoantiguedad,
            ]);
         }

         if ($request->montoBono != 0) {
            DB::table('bono_sueldo')->insert([
               'bono_id' => '9',
               'sueldo_id' => $sueldo_id,
               'user_id' => $userId, // o el user_id que corresponda
               'monto' => $request->montoBono,
            ]);
         }

         $tipopersonal = Personal::where('user_id', $userId)->value('tipo_id');

         if ($tipopersonal && $tipopersonal == 1) {
            DB::table('descuento_sueldo')->insert([
               'descuento_id' => '5',                // Aquí va el valor individual
               'sueldo_id'    => $sueldo_id,
               'user_id'      => $request->idUsuario,
               'monto'        => $request->gestora,
            ]);
         } else {

            DB::table('descuento_sueldo')->insert([
               'descuento_id' => '5',                // Aquí va el valor individual
               'sueldo_id'    => $sueldo_id,
               'user_id'      => $request->idUsuario,
               'monto'        => 0.00,
            ]);
         }


         foreach ($request->escogidos as $esc) {

            DB::table('descuento_sueldo')->insert([
               'descuento_id' => $esc,                // Aquí va el valor individual
               'sueldo_id'    => $sueldo_id,
               'user_id'      => $request->idUsuario,
               'monto'        => $request->$esc,
            ]);
         }


         /* Aqui terminaba el foreach */


         $descuentos = Descuento::all();
         $bonos = Bono::all();
         $user = User::where('id', '!=', 1)->get();
         session()->flash('success', '¡El sueldo fue registrado exitosamente!');
         DB::commit();
         return redirect()
            ->route('PagoSueldoIndividual.index') // la ruta que muestra tu formulario
            ->with(['user' => $user, 'bonos' => $bonos, 'descuentos' => $descuentos]);
      } catch (QueryException $e) {
         DB::rollBack();
         return back()->with('error', 'Error al pagar el sueldo' . $e->getMessage());
      }
   }

   /**
    * Display the specified resource.
    */
   public function show(string $id)
   {
      //
   }

   /**
    * Show the form for editing the specified resource.
    */
   public function edit(string $id)
   {
      //
   }

   /**
    * Update the specified resource in storage.
    */
   public function update(Request $request, string $id)
   {
      //
   }

   /**
    * Remove the specified resource from storage.
    */
   public function destroy(string $id)
   {
      //
   }

   public function calculaAntiguedad($fecha, $fec_ingreso)
   {
      $fechaAnterior = Carbon::parse($fecha);
      $fecha_ingreso = Carbon::parse($fec_ingreso);

      $diff = $fechaAnterior->diff($fecha_ingreso);
      $anios = $diff->y;
      $meses = $diff->m;
      $aniosDecimal = $anios + ($meses / 12);
      return $aniosDecimal;
   }

   public function obtenIdAntiguedad($fecha, $fec_ingreso)
   {
      $fechaAnterior = Carbon::parse($fecha);
      $fecha_ingreso = Carbon::parse($fec_ingreso);

      $diff = $fechaAnterior->diff($fecha_ingreso);
      $anios = $diff->y;
      $meses = $diff->m;
      $aniosDecimal = $anios + ($meses / 12);
      $aniosDecimal;

      if ($aniosDecimal < 2) {
         return 1;
      } elseif ($aniosDecimal > 2 && $aniosDecimal <= 5) {
         return 2;
      } elseif ($aniosDecimal > 5 && $aniosDecimal <= 8) {
         return 3;
      } elseif ($aniosDecimal > 8 && $aniosDecimal <= 11) {
         return 4;
      } elseif ($aniosDecimal > 11 && $aniosDecimal <= 15) {
         return 5;
      } elseif ($aniosDecimal > 15 && $aniosDecimal <= 20) {
         return 6;
      } elseif ($aniosDecimal > 20 && $aniosDecimal <= 25) {
         return 7;
      } elseif ($aniosDecimal > 25) {
         return 8;
      }
   }

   public function calculaSueldo($cargoId, $usr_id, $fechapago)
   {
      $fechapago = Carbon::parse($fechapago);
      $mes = $fechapago->format('m');
      $anio = $fechapago->format('Y');
      $gestion = $anio;
      $gestion_id = Gestion::where('nombre', $gestion)->value('id');

      $smn = DB::table('salario_minimos')
         ->where('gestion_id', $gestion_id)
         ->value('monto');

      $haberbasico = DB::table('haber_basicos as hb')
         ->join('users as u', 'u.cargo_id', '=', 'hb.cargo_id')
         ->where('u.id', $usr_id)
         ->orderByDesc('hb.id')
         ->limit(1)
         ->value('hb.monto');

      $fecha_ingreso = User::where('id', $usr_id)->value('fec_ingreso');

      $ant = $this->calculaAntiguedad($fechapago, $fecha_ingreso);

      if ($ant > 2 && $ant <= 5) {
         $bonoant = $smn * 0.05;
         $haberbasico += $bonoant;
         $descuento = $haberbasico * 0.1271;
         $haberbasico -= $descuento;
         $haberbasico = round($haberbasico, 2);
      } elseif ($ant > 5 && $ant <= 8) {
         $bonoant = $smn * 0.11;
         $haberbasico += $bonoant;
         $descuento = $haberbasico * 0.1271;
         $haberbasico -= $descuento;
         $haberbasico = round($haberbasico, 2);
      } elseif ($ant > 8 && $ant <= 11) {
         $bonoant = $smn * 0.18;
         $haberbasico += $bonoant;
         $descuento = $haberbasico * 0.1271;
         $haberbasico -= $descuento;
         $haberbasico = round($haberbasico, 2);
      } elseif ($ant > 11 && $ant <= 15) {

         $bonoant = $smn * 0.26;
         $haberbasico += $bonoant;
         $descuento = $haberbasico * 0.1271;
         $haberbasico -= $descuento;
         $haberbasico = round($haberbasico, 2);
      } elseif ($ant > 15 && $ant <= 20) {
         $bonoant = $smn * 0.34;
         $haberbasico += $bonoant;
         $descuento = $haberbasico * 0.1271;
         $haberbasico -= $descuento;
         $haberbasico = round($haberbasico, 2);
      } elseif ($ant > 20 && $ant <= 25) {
         $bonoant = $smn * 0.42;
         $haberbasico += $bonoant;
         $descuento = $haberbasico * 0.1271;
         $haberbasico -= $descuento;
         $haberbasico = round($haberbasico, 2);
      } elseif ($ant > 25) {
         $bonoant = $smn * 0.50;
         $haberbasico += $bonoant;
         $descuento = $haberbasico * 0.1271;
         $haberbasico -= $descuento;
         $haberbasico = round($haberbasico, 2);
      } else {
         $descuento = $haberbasico * 0.1271;
         $haberbasico -= $descuento;
         $haberbasico = round($haberbasico, 2);
      }
      return $haberbasico;
   }


   public function seleccionaSueldos(Request $request)
   {

      $validator = Validator::make($request->all(), [
         'fechapago' => 'required|date|before_or_equal:today',

      ],);

      if ($validator->fails()) {
         return redirect()->back()->withErrors($validator)->withInput();
      }



      $fecha = Carbon::parse($request->fechapago);
      $mes = $fecha->format('m');
      $anio = $fecha->format('Y');
      $gestion = $anio;
      $gestionId = Gestion::where('nombre', $gestion)->value('id');

      $datos = [];
      $datos =  DB::table('users as u')
         ->join('cargos as c', 'u.cargo_id', '=', 'c.id')
         ->join('haber_basicos as h', 'c.id', '=', 'h.cargo_id')
         ->where('h.gestion_id', $gestionId)
         ->where(function ($q) use ($mes) {
            $q->where('u.id', '!=', '1');
         })
         ->select(
            'u.id',
            'u.nombre',
            'u.apellido',
            'u.cargo_id',
            'u.fec_ingreso',
            'c.nombre as nombre_cargo',
            'h.monto'
         )
         ->get();

      $smn = DB::table('salario_minimos')
         ->where('gestion_id', $gestionId)
         ->value('monto');

      foreach ($datos as $dat) {
         $ant = $this->calculaAntiguedad($fecha, $dat->fec_ingreso);

         if ($ant > 2 && $ant <= 5) {
            $bonoant = $smn * 0.05;
            $dat->monto += $bonoant;
            $descuento = $dat->monto * 0.1271;
            $dat->monto -= $descuento;
            $dat->monto = round($dat->monto, 2);
         } elseif ($ant > 5 && $ant <= 8) {
            $bonoant = $smn * 0.11;
            $dat->monto += $bonoant;
            $descuento = $dat->monto * 0.1271;
            $dat->monto -= $descuento;
            $dat->monto = round($dat->monto, 2);
         } elseif ($ant > 8 && $ant <= 11) {
            $bonoant = $smn * 0.18;
            $dat->monto += $bonoant;
            $descuento = $dat->monto * 0.1271;
            $dat->monto -= $descuento;
            $dat->monto = round($dat->monto, 2);
         } elseif ($ant > 11 && $ant <= 15) {

            $bonoant = $smn * 0.26;
            $dat->monto += $bonoant;
            $descuento = $dat->monto * 0.1271;
            $dat->monto -= $descuento;
            $dat->monto = round($dat->monto, 2);
         } elseif ($ant > 15 && $ant <= 20) {
            $bonoant = $smn * 0.34;
            $dat->monto += $bonoant;
            $descuento = $dat->monto * 0.1271;
            $dat->monto -= $descuento;
            $dat->monto = round($dat->monto, 2);
         } elseif ($ant > 20 && $ant <= 25) {
            $bonoant = $smn * 0.42;
            $dat->monto += $bonoant;
            $descuento = $dat->monto * 0.1271;
            $dat->monto -= $descuento;
            $dat->monto = round($dat->monto, 2);
         } elseif ($ant > 25) {
            $bonoant = $smn * 0.50;
            $dat->monto += $bonoant;
            $descuento = $dat->monto * 0.1271;
            $dat->monto -= $descuento;
            $dat->monto = round($dat->monto, 2);
         } else {
            $descuento = $dat->monto * 0.1271;
            $dat->monto -= $descuento;
            $dat->monto = round($dat->monto, 2);
         }
      }
      $cont = 1;

      return view('Administrador.FormPagaSueldos')->with(['sueldos' => $datos, 'cont' => $cont, 'fechapago' => $fecha]);
   }

   public function muestraformfecha(Request $request)
   {

      return view('Administrador.FormSeleccionaSueldos');
   }
}

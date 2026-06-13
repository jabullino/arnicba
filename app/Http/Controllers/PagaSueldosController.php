<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\SalarioMinimo;
use App\Models\Asiento;
use App\Models\Gestion;
use App\Models\TipoCambioCompra;
use App\Models\TipoCambioVenta;
use App\Models\Cargo;
use App\Models\User;
use App\Models\Sueldo;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use App\Models\Personal;

class PagaSueldosController extends Controller
{


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
public function pagaSueldos(Request $request)
{

    // Validación
    $validator = Validator::make($request->all(), [
        'fechapago' => 'required|date|before_or_equal:today',
        'escogidos' => 'required|array',
    ], [
        'escogidos.required' => 'Debes seleccionar al menos un usuario.',
        'escogidos.array' => 'El formato de selección es inválido.',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    try {

        DB::beginTransaction();

        $fechapago = Carbon::parse($request->fechapago)->format('Y-m-d');

        $escogidos = $request->input('escogidos', []);

        $cargoIds = $request->input('cargos', []);


        // Tipos de cambio
        $valortipocambiocompra = (float) str_replace(',', '', TipoCambioCompra::latest('id')->value('tc'));

        $tipocambiocompra_id = TipoCambioCompra::latest('id')->value('id');

        $tipocambioventa_id = TipoCambioVenta::latest('id')->value('id');

        // Gestión
        $gestion = Carbon::parse($fechapago)->format('Y');

        $gestion_id = Gestion::where('nombre', $gestion)->value('id');

        foreach ($escogidos as $userId) {

            $ultimoId = Asiento::latest('id')->value('id');

            $cargoId = User::where('id', $userId)->value('cargo_id');

            // VERIFICAR SI EL CARGO EXISTE Y ESTÁ ACTIVO
            $cargoActivo = Cargo::where('id', $cargoId)
                ->whereNull('deleted_at')
                ->exists();

            // SI EL CARGO ESTÁ ELIMINADO
            if (!$cargoActivo) {
                continue;
            }
            

            // Calcular sueldos
            $sueldoBs = (float) str_replace(
                ',',
                '',
                $this->calculaSueldo(
                    $cargoId,
                    $userId,
                    $request->fechapago
                )
            );
          
            $sueldoSus = $sueldoBs / $valortipocambiocompra;

            // Subcuenta según cargo
            $subcuenta = match ($cargoId) {
                2, 3 => 11,
                4 => 9,
                5, 6, 7 => 12,
                8, 9 => 10,
                10, 11 => 13,
                default => 14,
            };

            // Crear asiento
            Asiento::create([
                'gestion_id' => $gestion_id,
                'fec_asiento' => $fechapago,
                'tc_id' => $tipocambiocompra_id,
                'tv_id' => $tipocambioventa_id,
                'recibo' => $ultimoId + 1,
                'factura' => null,
                'cuenta' => '3',
                'sub_cuenta' => $subcuenta,
                'monto_bs' => $sueldoBs,
                'monto_sus' => $sueldoSus,
                'origenfondos_id' => '1',
                'tipomovimiento_id' => '1',
                'proyecto_id' => null,
                'estado_id' => 1,
            ]);

            $mes = Carbon::parse($fechapago)->subMonth()->format('m');

            $sueldo = Sueldo::create([
                'mes' => $mes,
                'total' => $sueldoBs,
                'gestion_id' => $gestion_id,
                'user_id' => $userId,
            ]);

            $fechadeingreso = User::where('id', $userId)->value('fec_ingreso');

            $haberbasico = $this->devuelvemontohaberbasico(
                $userId,
                $request->fechapago
            );

            $montoantiguedad = $this->devuelvemontoantiguedad(
                $request->fechapago,
                $fechadeingreso,
                $userId
            );

            DB::table('bono_sueldo')->insert([
                'bono_id' => '10',
                'sueldo_id' => $sueldo->id,
                'user_id' => $userId,
                'monto' => $montoantiguedad,
            ]);

            //AQUI SE HACE LA DIFERENCIA PARA QUE NO SE HAGAN DESCUENTO NI A SUBDIRECTOR NI ADMINISTRADOR

            $tipopersonal = Personal::where('user_id', $userId)->value('tipo_id');

            if ($tipopersonal && $tipopersonal == 1) {

                $descuentogestora = $this->devuelvemontogestora(
                    $fechapago,
                    $fechadeingreso,
                    $userId
                );

            } else {

                $descuentogestora = 0.00;
            }

            DB::table('descuento_sueldo')->insert([
                'descuento_id' => 5,
                'sueldo_id' => $sueldo->id,
                'user_id' => $userId,
                'monto' => $descuentogestora,
            ]);
        }

        // Obtener datos de usuarios activos con cargos activos
        $datos = User::withoutGlobalScopes()
            ->select(
                'users.nombre',
                'users.apellido',
                'users.cargo_id',
                'users.fec_ingreso',
                'cargos.nombre as cargo_nombre',
                DB::raw('(SELECT hb.monto 
                    FROM haber_basicos hb 
                    WHERE hb.cargo_id = users.cargo_id 
                    ORDER BY hb.id DESC 
                    LIMIT 1) as ultimo_monto')
            )
            ->join('cargos', 'cargos.id', '=', 'users.cargo_id')
            ->where('users.id', '!=', 1)
            ->whereNull('users.deleted_at')
            ->whereNull('cargos.deleted_at')
            ->get();

        $smn = SalarioMinimo::latest()->value('monto');

        foreach ($datos as $dat) {

            $ant = $this->calculaAntiguedad(
                $request->fechapago,
                $dat->fec_ingreso
            );

            if ($ant > 2) {

                $bonoant = match (true) {
                    $ant <= 5 => $smn * 0.05,
                    $ant <= 8 => $smn * 0.11,
                    $ant <= 11 => $smn * 0.18,
                    $ant <= 15 => $smn * 0.26,
                    $ant <= 20 => $smn * 0.34,
                    $ant <= 25 => $smn * 0.42,
                    default => $smn * 0.50,
                };

                $dat->ultimo_monto += $bonoant;
            }

            $dat->ultimo_monto -= $dat->ultimo_monto * 0.1271;

            $dat->ultimo_monto = number_format(
                $dat->ultimo_monto,
                2,
                '.',
                ','
            );
        }

        session()->flash('success', '¡Sueldos pagados exitosamente!');

        DB::commit();

        return redirect()
            ->route('muestraformfechasueldos')
            ->with('success', 'Sueldos pagados correctamente');

    } catch (QueryException $e) {

        DB::rollBack();

        return back()->with(
            'error',
            'No se pudo efectuar el pago de sueldos ' . $e->getMessage()
        );
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
         ->orderByDesc('id')
         ->value('monto');


     $haberbasico = DB::table('haber_basicos as hb')
       ->join('users as u', 'u.cargo_id', '=', 'hb.cargo_id')
       ->where('u.id', $usr_id)
       ->latest('hb.created_at')
       ->value('hb.monto');

      $fecha_ingreso = User::where('id', $usr_id)->value('fec_ingreso');

      $ant = $this->calculaAntiguedad($fechapago, $fecha_ingreso);

      if ($ant > 2 && $ant <= 5) {
         $bonoant = $smn * 0.05;
         $haberbasico += $bonoant;
         if (($cargoId != 2) && ($cargoId!=7) && ($cargoId!=10)) {
            $descuento = $haberbasico * 0.1271;
            $haberbasico -= $descuento;
         }
         $haberbasico = round($haberbasico, 2);
      } elseif ($ant > 5 && $ant <= 8) {
         $bonoant = $smn * 0.11;
         $haberbasico += $bonoant;
         if (($cargoId != 2) && ($cargoId!=7) && ($cargoId!=10)) {
            $descuento = $haberbasico * 0.1271;
            $haberbasico -= $descuento;
         }
         $haberbasico = round($haberbasico, 2);
      } elseif ($ant > 8 && $ant <= 11) {
         $bonoant = $smn * 0.18;
         $haberbasico += $bonoant;
         if (($cargoId != 2) && ($cargoId!=7) && ($cargoId!=10)) {
            $descuento = $haberbasico * 0.1271;
            $haberbasico -= $descuento;
         }
         $haberbasico = round($haberbasico, 2);
      } elseif ($ant > 11 && $ant <= 15) {

         $bonoant = $smn * 0.26;
         $haberbasico += $bonoant;
         if (($cargoId != 2) && ($cargoId!=7) && ($cargoId!=10)) {
            $descuento = $haberbasico * 0.1271;
            $haberbasico -= $descuento;
         }
         $haberbasico = round($haberbasico, 2);
      } elseif ($ant > 15 && $ant <= 20) {
         $bonoant = $smn * 0.34;
         $haberbasico += $bonoant;
         if (($cargoId != 2) && ($cargoId!=7) && ($cargoId!=10)) {
            $descuento = $haberbasico * 0.1271;
            $haberbasico -= $descuento;
         }
         $haberbasico = round($haberbasico, 2);
      } elseif ($ant > 20 && $ant <= 25) {
         $bonoant = $smn * 0.42;
         $haberbasico += $bonoant;
         if (($cargoId != 2) && ($cargoId!=7) && ($cargoId!=10) ) {
            $descuento = $haberbasico * 0.1271;
            $haberbasico -= $descuento;
         }
         $haberbasico = round($haberbasico, 2);
      } elseif ($ant > 25) {
         $bonoant = $smn * 0.50;
         $haberbasico += $bonoant;
         if (($cargoId != 2) && ($cargoId!=7) && ($cargoId!=10) ) {
            $descuento = $haberbasico * 0.1271;
            $haberbasico -= $descuento;
         }
         $haberbasico = round($haberbasico, 2);
      } else {
         if (($cargoId != 2) && ($cargoId!=7) && ($cargoId!=10) ) {
            $descuento = $haberbasico * 0.1271;
            $haberbasico -= $descuento;
         }
         $haberbasico = round($haberbasico, 2);
      }
      return $haberbasico;
   }


public function seleccionaSueldos(Request $request)
{
    $validator = Validator::make($request->all(), [
        'fechapago' => 'required|date|before_or_equal:today',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    $fechapago = Carbon::parse($request->fechapago);
    $mes = $fechapago->format('m');
    $anio = $fechapago->format('Y');
    $gestion = $anio;
    $gestion_id = Gestion::where('nombre', $gestion)->value('id');

    $mescomparacion = $fechapago->copy()->subMonth()->format('m');

    $verificagestion = Sueldo::where('gestion_id', $gestion_id)
        ->where('mes', (int)$mescomparacion)
        ->first();

    if ($verificagestion != null) {
        session()->flash('success', '¡El mes que intenta cancelar ya esta registrado en el sistema!');
        return redirect()->back()->with([
            'sueldospagados' => 'El mes que intenta pagar ya está registrado'
        ]);
    }

    $fecha = Carbon::parse($request->fechapago);
    $gestionId = Gestion::where('nombre', $fecha->format('Y'))->value('id');

    // 🔥 AQUÍ ESTÁ LA CORRECCIÓN
    $datos = DB::table('users as u')
        ->join('cargos as c', 'u.cargo_id', '=', 'c.id')
        ->join(DB::raw('(
            SELECT hb1.*
            FROM haber_basicos hb1
            INNER JOIN (
                SELECT cargo_id, MAX(id) as max_id
                FROM haber_basicos
                GROUP BY cargo_id
            ) hb2 
            ON hb1.cargo_id = hb2.cargo_id 
            AND hb1.id = hb2.max_id
        ) as h'), 'c.id', '=', 'h.cargo_id')
        ->where('h.gestion_id', $gestionId)
        ->where('u.id', '!=', 1)
       ->whereNull('u.deleted_at') // <- aquí 
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
        ->orderByDesc('id')
        ->value('monto');

    foreach ($datos as $dat) {
        $ant = $this->calculaAntiguedad($fecha, $dat->fec_ingreso);

        if ($ant > 2 && $ant <= 5) {
            $bonoant = $smn * 0.05;
        } elseif ($ant > 5 && $ant <= 8) {
            $bonoant = $smn * 0.11;
        } elseif ($ant > 8 && $ant <= 11) {
            $bonoant = $smn * 0.18;
        } elseif ($ant > 11 && $ant <= 15) {
            $bonoant = $smn * 0.26;
        } elseif ($ant > 15 && $ant <= 20) {
            $bonoant = $smn * 0.34;
        } elseif ($ant > 20 && $ant <= 25) {
            $bonoant = $smn * 0.42;
        } elseif ($ant > 25) {
            $bonoant = $smn * 0.50;
        } else {
            $bonoant = 0;
        }

        $dat->monto += $bonoant;

        if ($dat->cargo_id !=7 && $dat->cargo_id!=10) {
            $descuento = $dat->monto * 0.1271;
            $dat->monto -= $descuento;
        }

        $dat->monto = round($dat->monto, 2);
    }

    $cont = 1;


    return view('Administrador.FormPagaSueldos')->with([
        'sueldos' => $datos,
        'cont' => $cont,
        'fechapago' => $fecha
    ]);
}

   public function devuelvemontohaberbasico($userId, $fechapago)
   {

      $fechapago = Carbon::parse($fechapago);
      $mes = $fechapago->format('m');
      $anio = $fechapago->format('Y');
      $gestionId = Gestion::where('nombre', $anio)->value('id');

      $cargoId = DB::table('users')
         ->where('id', $userId)
         ->value('cargo_id');

      if (!$cargoId) {
         return null; // El usuario no tiene cargo asignado
      }

      // 3️⃣ Buscar el monto correspondiente en la tabla haber_basicos
      $haberbasico = DB::table('haber_basicos')
                     ->where('cargo_id', $cargoId)
                     ->where('gestion_id', $gestionId)
                     ->where('monto', '>', 0)
                     ->orderByDesc('id')
                     ->value('monto');

      // 4️⃣ Devolver el monto encontrado (o 0 si no existe)
      return $haberbasico ?? 0;
   }

   public function devuelvemontoantiguedad($fechapago, $fec_ingreso, $userId)
   {

      $fechapago = Carbon::parse($fechapago);
      $mes = $fechapago->format('m');
      $anio = $fechapago->format('Y');
      $gestion = $anio;
      $gestion_id = Gestion::where('nombre', $gestion)->value('id');
      $smn = DB::table('salario_minimos')
         ->orderByDesc('id')
         ->value('monto');


      $ant = $this->calculaAntiguedad($fechapago, $fec_ingreso, $userId);

      if ($ant > 2 && $ant <= 5) {
         $bonoant = $smn * 0.05;
         $bonoant = round($bonoant, 2);
      } elseif ($ant > 5 && $ant <= 8) {
         $bonoant = $smn * 0.11;
         $bonoant = round($bonoant, 2);
      } elseif ($ant > 8 && $ant <= 11) {
         $bonoant = $smn * 0.18;
         $bonoant = round($bonoant, 2);
      } elseif ($ant > 11 && $ant <= 15) {
         $bonoant = $smn * 0.26;
         $bonoant = round($bonoant, 2);
      } elseif ($ant > 15 && $ant <= 20) {
         $bonoant = $smn * 0.34;
         $bonoant = round($bonoant, 2);
      } elseif ($ant > 20 && $ant <= 25) {
         $bonoant = $smn * 0.42;
         $bonoant = round($bonoant, 2);
      } elseif ($ant > 25) {
         $bonoant = $smn * 0.50;
         $bonoant = round($bonoant, 2);
      } else {
         $bonoant = 0.00;
      }

      return $bonoant;
   }

   public function devuelvemontogestora($fechapago, $fec_ingreso, $userId)
   {
      $fechapago = Carbon::parse($fechapago);
      $mes = $fechapago->format('m');
      $anio = $fechapago->format('Y');
      $gestion = $anio;
      $gestion_id = Gestion::where('nombre', $gestion)->value('id');

      $smn = DB::table('salario_minimos')
         ->orderByDesc('id')
         ->value('monto');

      $ant = $this->calculaAntiguedad($fechapago, $fec_ingreso, $userId);

      $haberbasico = $this->devuelvemontohaberbasico($userId, $fechapago);
      if ($ant >= 2 && $ant < 5) {
         $bonoant = $smn * 0.05;
         $haberbasico += $bonoant;
         $descuento = $haberbasico * 0.1271;
         $descuento = round($descuento, 2);
      } elseif ($ant >= 5 && $ant < 8) {
         $bonoant = $smn * 0.11;
         $haberbasico += $bonoant;
         $descuento = $haberbasico * 0.1271;
         $descuento = round($descuento, 2);
      } elseif ($ant >= 8 && $ant < 11) {
         $bonoant = $smn * 0.18;
         $haberbasico += $bonoant;
         $descuento = $haberbasico * 0.1271;
         $descuento = round($descuento, 2);
      } elseif ($ant >= 11 && $ant < 15) {
         $bonoant = $smn * 0.26;
         $haberbasico += $bonoant;
         $descuento = $haberbasico * 0.1271;
         $descuento = round($descuento, 2);
      } elseif ($ant >= 15 && $ant < 20) {
         $bonoant = $smn * 0.34;
         $haberbasico += $bonoant;
         $descuento = $haberbasico * 0.1271;
         $descuento = round($descuento, 2);
      } elseif ($ant >= 20 && $ant < 25) {
         $bonoant = $smn * 0.42;
         $haberbasico += $bonoant;
         $descuento = $haberbasico * 0.1271;
         $descuento = round($descuento, 2);
      } elseif ($ant >= 25) {
         $bonoant = $smn * 0.50;
         $haberbasico += $bonoant;
         $descuento = $haberbasico * 0.1271;
         $descuento = round($descuento, 2);
      } else {
         $descuento = $haberbasico * 0.1271;
         $descuento = round($descuento, 2);
      }

      $descuento = str_replace(',', '', $descuento);
      $descuento = (float) $descuento;
      $descuento = round($descuento, 2);
      return $descuento;
   }

   public function muestraformfecha(Request $request)
   {

      return view('Administrador.FormSeleccionaSueldos');
   }
}

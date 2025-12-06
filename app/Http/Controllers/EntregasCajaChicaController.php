<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EntregasCajaChica;
use App\Models\CajaChica;
use App\Models\Gestion;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Database\QueryException;

class EntregasCajaChicaController extends Controller
{
    /**
     * Mostrar todas las entregas en la vista index.
     */
    public function index(Request $request)
    {
        // Todas las gestiones disponibles para los selects
        $gestiones = Gestion::select('id', 'nombre')->orderByDesc('nombre')->get();

        // Array de meses para los selects
        $meses = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre'
        ];

        // Entregas no se envían porque se cargarán por AJAX
        return view('AdminSis.EntregasCajaChica.index', compact('gestiones', 'meses'));
    }



    /**
     * Mostrar formulario para crear nueva entrega.
     */
    public function create()
    {
        
         $gestiones = Gestion::whereNull('deleted_at')->get();
        $meses = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre'
        ];
           
        return view('AdminSis.EntregasCajaChica.create', compact('gestiones', 'meses'));
    }


    /**
     * Guardar nueva entrega en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([

            'fecha_entrega' => 'required|date',
            'monto' => 'required|numeric|min:0',
            'mes' => 'required|integer|between:1,12',
        ]);

        try{
        DB::beginTransaction();
        $cajachica_id = CajaChica::where('gestion_id', $request->gestion_id)->value('id');
        
        EntregasCajaChica::create([
            'cajachica_id' => $cajachica_id,
            'fecha_entrega' => $request->fecha_entrega,
            'mes' => $request->mes,
            'monto' => $request->monto,
            'saldo' =>  $request->monto,
        ]);
         DB::commit();
        session()->flash('success', '¡Entrega creada exitosamente!');
        
        return redirect()->route('entregascajachicas.index')
            ->with('success', 'Entrega creada correctamente.');
        
        }catch (QueryException $e) {
            DB::rollBack();
            return back()->with('success', 'Se guardó la entrega de caja chic' . $e->getMessage());
        }
    }


    /**
     * Mostrar detalles de una entrega.
     */
    public function show($id)
    {
         $meses = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre'
        ];
        $entrega = EntregasCajaChica::findOrFail($id);
        return view('AdminSis.EntregasCajaChica.show', compact('entrega','meses'));
    }

    /**
     * Mostrar formulario para editar una entrega.
     */
    public function edit($id)
{
    $meses = [
        1 => 'Enero',
        2 => 'Febrero',
        3 => 'Marzo',
        4 => 'Abril',
        5 => 'Mayo',
        6 => 'Junio',
        7 => 'Julio',
        8 => 'Agosto',
        9 => 'Septiembre',
        10 => 'Octubre',
        11 => 'Noviembre',
        12 => 'Diciembre'
    ];

    $entrega = EntregasCajaChica::findOrFail($id);
    return view('AdminSis.EntregasCajaChica.edit', compact('entrega', 'meses'));
}

    /**
     * Actualizar una entrega existente.
     */
    public function update(Request $request, $id)
{
    try{
        DB::beginTransaction();
    $entrega = EntregasCajaChica::findOrFail($id);

    $request->validate([
        'fecha_entrega' => 'required|date',
        'monto' => 'required|numeric|min:0',
        'mes' => 'required|integer|min:1|max:12',
        'descripcion' => 'nullable|string',
    ]);

    // Asegurarnos que mes es numérico
    $mes = (int) $request->mes;

    // Cambiar el mes en la fecha
    $fecha = Carbon::parse($request->fecha_entrega)->setMonth($mes);

    $entrega->update([
        'fecha_entrega' => $fecha,
        'monto' => $request->monto,
        'descripcion' => $request->descripcion,
    ]);
    session()->flash('success', '¡Entrega modificada exitosamente!');
     DB::commit();
    return redirect()
        ->route('entregascajachicas.index')
        ->with('success', 'Entrega actualizada correctamente.');
    
   }catch (QueryException $e) {
            DB::rollBack();
            return back()->with('error', 'No se pudo actualizar la entrega' . $e->getMessage());
        }
}
    /**
     * Eliminar una entrega.
     */
    public function destroy($id)
    {
        try{
            DB::beginTransaction();
         $entrega = EntregasCajaChica::findOrFail($id);

    $entrega->delete(); // Esto hará soft delete
    session()->flash('success', '¡Entrega eliminada exitosamente!');
    DB::commit();
    return response()->json([
        'success' => true,
        'message' => 'Entrega eliminada correctamente.'
    ]);
    
    }catch (QueryException $e) {
            DB::rollBack();
            return back()->with('error', 'No se pudo eliminar la entrega' . $e->getMessage());
        }
    }

    public function filtro(Request $request)
    {
        $anio = $request->get('anio');
        $mes = $request->get('mes');

        if (!$anio || !$mes) {
            return response()->json(['html' => ''], 200);
        }

        $entregas = EntregasCajaChica::with(['cajachica.gestion'])
            ->where('mes', $mes)
            ->whereHas('cajachica.gestion', function ($q) use ($anio) {
                $q->where('nombre', $anio);
            })
            ->get();

        $meses = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre'
        ];

        $html = view('AdminSis.EntregasCajaChica.partials.tabla', compact('entregas', 'meses'))->render();

        return response()->json(['html' => $html]);
    }

    public function filtrar(Request $request)
    {
        $anio = $request->get('anio');
        $mes = $request->get('mes');

        $query = EntregasCajaChica::with(['cajachica.gestion']);

        if ($anio) {
            $query->whereHas('cajachica.gestion', function ($q) use ($anio) {
                $q->where('id', $anio);
            });
        }

        if ($mes) {
            $query->where('mes', $mes);
        }

        $entregas = $query->get();

        return response()->json($entregas);
    }
    public function ajaxEntregas(Request $request)
    {
        $anio = $request->get('anio');
        $mes = $request->get('mes');

        $query = EntregasCajaChica::with('cajachica.gestion');

        if ($anio) {
            $query->whereHas('cajachica.gestion', function ($q) use ($anio) {
                $q->where('id', $anio);
            });
        }

        if ($mes) {
            $query->where('mes', $mes);
        }

        $entregas = $query->get();

        // Retornamos un JSON con la información necesaria
        return response()->json($entregas);
    }

    public function porGestion($gestionId)
    {
        $cajas = CajaChica::where('gestion_id', $gestionId)->get();
        return response()->json($cajas);
    }


    public function ajax(Request $request)
    {
        // Obtener parámetros
        $anio = $request->get('anio');
        $mes  = $request->get('mes');

        // Construir query con relaciones
        $query = EntregasCajaChica::with('cajachica');

        if ($anio) {
            $query->whereHas('cajachica.gestion', function ($q) use ($anio) {
                $q->where('nombre', $anio);
            });
        }

        if ($mes) {
            $query->where('mes', $mes);
        }

        $entregas = $query->get();

        // Retornar JSON
        return response()->json($entregas);
    }
    //esta funcion saca las entregas con totales para el area de administrador


}

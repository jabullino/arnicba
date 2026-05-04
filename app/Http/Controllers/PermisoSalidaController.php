<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gestion;
use App\Models\PermisoSalida;
use App\Models\Cargo;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class PermisoSalidaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permisos = \App\Models\PermisoSalida::select(
                'permiso_salidas.*',
                'gestiones.nombre as gestion_nombre',
                'users.nombre as user_nombre',
                'users.apellido as user_apellido'
            )
            ->join('gestiones', 'permiso_salidas.gestion_id', '=', 'gestiones.id')
            ->join('users', 'permiso_salidas.user_id', '=', 'users.id')
            ->where(function ($query) {
                $query->where('permiso_salidas.aprobado', false)
                      ->orWhereNull('permiso_salidas.aprobado');
            })
            ->orderBy('permiso_salidas.id', 'desc')
            ->get();
    
        // 👇 detecta si estás en AdminSis
        $modo = request()->is('AdminSis/*') ? 'admin' : 'administrador';
    
        return view('Administrador.Permisos.ListaPermisos', compact('permisos', 'modo'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
{
    $nextId = PermisoSalida::max('id') + 1;

    $fecha = Carbon::now()->format('Ymd');
    $numPermiso = $fecha . '/' . $nextId;
    $gestiones = Gestion::all();
    $cargos = Cargo::where('id', '!=', 1)->get();

    return view('Administrador.Permisos.PermisoSalida', compact('numPermiso','gestiones','cargos'));
}

    /**
     * Store a newly created resource in storage.
     */
   
public function store(Request $request)
{
    // ✅ VALIDACIÓN
    $request->validate([
        'gestion_id'       => 'required|exists:gestiones,id',
        'institucion'      => 'required|string|max:100',
        'motivo'           => 'required|string|max:300',
        'fecha_solicitud'  => 'required|date',
        'fecha_salida'     => 'required|date',
        'hora_salida'      => 'required',
        'hora_retorno'     => 'required',
        'destino'          => 'required|string|max:100',
        'observaciones'    => 'nullable|string|max:300',
        'cargo'            => 'required|exists:cargos,id',
        'empleado'         => 'required|exists:users,id',
    ]);

    // ✅ CREAR REGISTRO
    $permiso = new PermisoSalida();

    $permiso->gestion_id = $request->gestion_id;

    // 🔴 CLAVE: valor temporal para evitar error MySQL
    $permiso->num_permiso = 'TEMP';

    $permiso->institucion = $request->institucion;
    $permiso->motivo = $request->motivo;
    $permiso->fecha_solicitud = $request->fecha_solicitud;
    $permiso->fecha_salida = $request->fecha_salida;
    $permiso->hora_salida = $request->hora_salida;

    // ✅ CAMPOS OPCIONALES
    $permiso->hora_retorno = $request->hora_retorno ?: null;
    $permiso->observaciones = $request->observaciones ?: null;

    $permiso->destino = $request->destino;
    $permiso->estado = 'pendiente';

    $permiso->cargo_id = $request->cargo;
    $permiso->user_id = $request->empleado;

    // 1️⃣ GUARDAR (para obtener ID)
    $permiso->save();

    // 2️⃣ GENERAR NÚMERO REAL
    $permiso->num_permiso = now()->format('Ymd') . '/' . $permiso->id;

    // 3️⃣ ACTUALIZAR
    $permiso->save();

    // ✅ RESPUESTA
    return redirect()->back()->with('success', 'Permiso registrado correctamente');
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
    public function edit($id)
    {
        $permisos = \App\Models\PermisoSalida::select(
                'permiso_salidas.*',
                'gestiones.nombre as gestion_nombre',
                'users.nombre as user_nombre',
                'users.apellido as user_apellido'
            )
            ->join('gestiones', 'permiso_salidas.gestion_id', '=', 'gestiones.id')
            ->join('users', 'permiso_salidas.user_id', '=', 'users.id')
            ->orderBy('permiso_salidas.id', 'desc')
            ->get();
    
        $modo = 'admin';
    
        return view('AdminSis.Permisos.ListaPermisos', compact('permisos', 'modo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $permiso = \App\Models\PermisoSalida::findOrFail($id);
    
        $permiso->estado = 'autorizado';
        $permiso->save();
    
        return response()->json([
            'success' => true
        ]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
  
public function imprimirPDF($id)
{
    $permiso = \App\Models\PermisoSalida::select(
            'permiso_salidas.*',
            'gestiones.nombre as gestion_nombre',
            'users.nombre as user_nombre',
            'users.apellido as user_apellido'
        )
        ->join('gestiones', 'permiso_salidas.gestion_id', '=', 'gestiones.id')
        ->join('users', 'permiso_salidas.user_id', '=', 'users.id')
        ->where('permiso_salidas.id', $id)
        ->firstOrFail();

    // Firma privada (igual que ya haces)
    $path = storage_path('app/private/fotos/signature.png');
    $firmaBase64 = null;

    if (file_exists($path)) {
        $type = mime_content_type($path);
        $data = file_get_contents($path);
        $firmaBase64 = 'data:' . $type . ';base64,' . base64_encode($data);
    }

    $permiso->aprobado = true;
    $permiso->save();

    $pdf = Pdf::loadView('Administrador.Permisos.ImprimirPermiso', compact('permiso', 'firmaBase64'));

    return $pdf->download('permiso.pdf'); // o ->stream()
}
    
}

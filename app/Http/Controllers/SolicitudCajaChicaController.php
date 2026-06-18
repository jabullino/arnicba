<?php

namespace App\Http\Controllers;

use App\Models\Gestion;
use App\Models\SolicitudCajaChica;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SolicitudCajaChicaController extends Controller
{
    public function index()
    {
        $solicitudes = SolicitudCajaChica::with('gestion', 'detalles')
           // ->whereNull('impreso')
            ->latest()
            ->get();

        // Detectas desde qué ruta entra
        $modo = request()->is('AdminSis/*') ? 'adminsis' : 'admin';
        
        return view('Administrador.CajaChica.index', compact('solicitudes', 'modo'));
    }

    public function create()
    {
        $gestion = Gestion::orderBy('id', 'desc')->first();

        $fecha = now()->format('Y-m-d');

        $contador = SolicitudCajaChica::where('fecha', $fecha)->count() + 1;

        $codigo = Carbon::parse($fecha)->format('dmY').'-'.$contador;

        return view('Administrador.CajaChica.create', compact('gestion', 'codigo'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'gestion_id' => 'required|exists:gestiones,id',
            'fecha' => 'required|date',
            'detalles' => 'required|array|min:1',
            'detalles.*.descripcion' => 'required|string',
            'detalles.*.cantidad' => 'required|numeric|min:0.01',
        ]);

        DB::beginTransaction();

        try {

            $fecha = Carbon::parse($request->fecha)->format('Y-m-d');

            $ultimo = SolicitudCajaChica::where('fecha', $fecha)->lockForUpdate()->count() + 1;

            $codigo = Carbon::parse($request->fecha)->format('dmY').'-'.$ultimo;

            $solicitud = SolicitudCajaChica::create([
                'gestion_id' => $request->gestion_id,
                'codigo' => $codigo,
                'fecha' => $request->fecha,
                'estado' => 'pendiente',
            ]);

            foreach ($request->detalles as $detalle) {
                $solicitud->detalles()->create($detalle);
            }

            DB::commit();

            // ── Notificación por correo ──────────────────────────────────────────
try {
    $solicitud->load('gestion', 'detalles');
    $total = $solicitud->detalles->sum('cantidad');

    $cuerpo  = "Nueva Solicitud de Caja Chica Arca de Rescate de los Niños\n";
    $cuerpo .= "==============================\n\n";
    $cuerpo .= "Código  : {$solicitud->codigo}\n";
    $cuerpo .= "Fecha   : " . Carbon::parse($solicitud->fecha)->format('d/m/Y') . "\n";
    $cuerpo .= "Gestión : " . ($solicitud->gestion->nombre ?? '—') . "\n\n";
    $cuerpo .= "DETALLE:\n";
    $cuerpo .= "-----------------------------\n";

    foreach ($solicitud->detalles as $i => $detalle) {
        $num    = str_pad($i + 1, 2, '0', STR_PAD_LEFT);
        $cuerpo .= "{$num}. {$detalle->descripcion} — Bs. " . number_format($detalle->cantidad, 2) . "\n";
    }

    $cuerpo .= "-----------------------------\n";
    $cuerpo .= "TOTAL   : Bs. " . number_format($total, 2) . "\n";

    Mail::raw($cuerpo, function ($message) use ($solicitud) {
        $message->to('arnibolcba@gmail.com')
                ->subject('Nueva Solicitud de Caja Chica – ' . $solicitud->codigo);
    });

} catch (\Exception $mailException) {
    Log::error('Error al enviar correo de caja chica: ' . $mailException->getMessage());
}
// ────────────────────────────────────────────────────────────────────

            return redirect()->route('solicitudes.index')
                ->with('success', 'Solicitud creada correctamente');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with('error', 'Error al guardar: '.$e->getMessage());
        }
    }

    public function edit($id)
    {
        $solicitud = SolicitudCajaChica::with('gestion', 'detalles')
            ->findOrFail($id);

        $solicitud->fecha = Carbon::parse($solicitud->fecha)->format('d-m-Y');

        return view('Administrador.CajaChica.edit', compact('solicitud'));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {

            $solicitud = SolicitudCajaChica::with('detalles')->findOrFail($id);

            // IDs marcados como rechazados
            $rechazados = $request->input('rechazados', []);

            // Todos los IDs de detalles
            $todos = $solicitud->detalles->pluck('id')->toArray();

            // 🔥 1. Marcar como eliminados (soft delete)
            if (! empty($rechazados)) {
                foreach ($rechazados as $detalleId) {
                    $detalle = $solicitud->detalles->where('id', $detalleId)->first();

                    if ($detalle) {
                        $detalle->deleted_at = Carbon::now();
                        $detalle->save();
                    }
                }
            }

            // 🔥 2. Determinar estado
            if (count($rechazados) === count($todos)) {
                // TODOS rechazados
                $solicitud->estado = 'rechazado';
            } else {
                // Parcial o ninguno
                $solicitud->estado = 'autorizado';
            }

            $solicitud->save();

            DB::commit();

            return redirect()
                ->route('adminsis.solicitudes.index')
                ->with('success', 'Solicitud procesada correctamente');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with('error', 'Error al procesar la solicitud');
        }
    }

   

    public function imprimir($id)
    {
        $solicitud = SolicitudCajaChica::with([
            'gestion',
            'detalles' => function ($q) {
                $q->whereNull('deleted_at');
            }
        ])->findOrFail($id);
    
        $total = $solicitud->detalles->sum('cantidad');
    
        $firmante = User::where('cargo_id', 4)->first();
    
        // FORMATEAR FECHA
        $fecha_formateada = Carbon::parse($solicitud->fecha)
            ->format('d-m-Y');
    
        if (is_null($solicitud->impreso)) {
            $solicitud->impreso = 1;
            $solicitud->save();
        }
    
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
            'Administrador.CajaChica.imprimir',
            compact(
                'solicitud',
                'total',
                'firmante',
                'fecha_formateada'
            )
        );
    
        $pdf->setPaper('Letter');
    
        return $pdf->stream('solicitud_'.$solicitud->id.'.pdf');
    }
}

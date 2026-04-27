<?php

namespace App\Http\Controllers;

use App\Models\Residente;
use App\Models\Ciudad;
use App\Models\Provincia;
use App\Models\Tipologia;
use App\Models\Extension;
use App\Models\Municipio;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class ReporteResidentesController extends Controller
{
    public function generarPDF()
    {
        $hoy = Carbon::now();

        // Cargar en memoria nombres de referencia
        $extensiones = Extension::pluck('nombre', 'id');
        $ciudades = Ciudad::pluck('nombre', 'id');
        $provincias = Provincia::pluck('nombre', 'id');
        $municipios = Municipio::pluck('nombre', 'id');

        // 🔹 Consulta educativa
        $educacion = DB::table('grado_residente_unidad_educativa as gru')
            ->leftJoin('residentes as r', 'gru.residente_id', '=', 'r.id')
            ->leftJoin('unidad_educativas as ue', 'gru.ue_id', '=', 'ue.id')
            ->leftJoin('grados as g', 'gru.grado_id', '=', 'g.id')
            ->leftJoin('cursos as c', 'gru.curso_id', '=', 'c.id')
            ->select(
                'r.id as residente_id',
                'ue.nombre as unidad_educativa',
                'g.nombre as grado',
                'c.nombre as curso'
            )
            ->get()
            ->groupBy('residente_id');

        // 🔹 Datos de residentes
        $residentes = Residente::with(['acogida', 'derivacion'])->get()->map(function ($residente) use (
            $hoy,
            $extensiones,
            $ciudades,
            $provincias,
            $municipios,
            $educacion
        ) {

            // 🔹 Fecha nacimiento (seguro)
            $edad = null;
            if (!empty($residente->fecnac)) {
                $fecnac = Carbon::parse($residente->fecnac);
                $edadAnios = intval($fecnac->diffInYears($hoy));
                $edadMeses = intval($fecnac->copy()->addYears($edadAnios)->diffInMonths($hoy));
                $edad = "{$edadAnios} años {$edadMeses} meses";
            }

            // 🔹 Fecha ingreso (seguro)
            $fecIngreso = !empty($residente->fec_ingreso)
                ? Carbon::parse($residente->fec_ingreso)
                : null;

            // 🔹 Estadía (seguro)
            $estadia = null;
            if ($fecIngreso) {
                $diff = $fecIngreso->diff($hoy);

                if ($diff->y > 0) {
                    $estadia = "{$diff->y} años {$diff->m} meses {$diff->d} días";
                } elseif ($diff->m > 0) {
                    $estadia = "{$diff->m} meses {$diff->d} días";
                } else {
                    $estadia = "{$diff->d} días";
                }
            }

            $nombreExtension = $residente->ext ? ($extensiones[$residente->ext] ?? null) : null;
            $nombreCiudad = $residente->ciudad ? ($ciudades[$residente->ciudad] ?? null) : null;
            $nombreProvincia = $residente->provincia ? ($provincias[$residente->provincia] ?? null) : null;

            $ciudadAcogida = $residente->acogida?->ciudad ? ($ciudades[$residente->acogida->ciudad] ?? null) : null;
            $municipioAcogida = $residente->acogida?->municipio ? ($municipios[$residente->acogida->municipio] ?? null) : null;

            $tipologiaAcogida = null;
            if ($residente->acogida?->tipologia) {
                $tipologiaAcogida = Tipologia::where('id', $residente->acogida->tipologia)->value('nombre');
            }

            $edu = $educacion->get($residente->id)?->last();
            $unidadEducativa = $edu->unidad_educativa ?? '';
            $curso = $edu->curso ?? '';
            $grado = $edu->grado ?? '';

            return [
                'nombre' => $residente->nombre,
                'apellido' => $residente->apellido,
                'ci' => $residente->ci,
                'extension' => $nombreExtension,
                'ciudad' => $nombreCiudad,
                'provincia' => $nombreProvincia,

                // 🔹 Formateo seguro
                'fecha_ingreso' => $fecIngreso
                    ? $fecIngreso->format('d-m-Y')
                    : null,

                'edad' => $edad,
                'estadia' => $estadia,
                'ciudad_acogida' => $ciudadAcogida,
                'municipio_acogida' => $municipioAcogida,
                'tipologia_acogida' => $tipologiaAcogida,
                'juzgado' => optional($residente->derivacion)->numjuzgado,
                'documento' => optional($residente->derivacion)->numdoc,
                'unidad_educativa' => $unidadEducativa,
                'curso' => $curso,
                'grado' => $grado,
            ];
        });

        // 🔹 HTML
        $html = '
        <style>
            body { font-family: DejaVu Sans, sans-serif; font-size: 9px; }
            table { width: 100%; border-collapse: collapse; }
            th, td {
                border: 1px solid #000;
                padding: 3px;
                text-align: left;
                word-wrap: break-word;
            }
            th {
                background-color: #f2f2f2;
            }
        </style>
        <h3 align="center">Reporte de Residentes</h3>
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>CI</th>
                    <th>Ext.</th>
                    <th>Ciudad</th>
                    <th>Provincia</th>
                    <th>Fecha Ingreso</th>
                    <th>Edad</th>
                    <th>Estadía</th>
                    <th>Ciudad Acogida</th>
                    <th>Municipio</th>
                    <th>Tipología</th>
                    <th>Juzgado</th>
                    <th>Documento</th>
                    <th>Unidad Educativa</th>
                    <th>Curso</th>
                    <th>Grado</th>
                </tr>
            </thead>
            <tbody>';

        foreach ($residentes as $r) {
           $html .= '<tr>
    <td>' . e($r['nombre']) . '</td>
    <td>' . e($r['apellido']) . '</td>
    <td>' . e($r['ci']) . '</td>
    <td>' . e($r['extension']) . '</td>
    <td>' . e($r['ciudad']) . '</td>
    <td>' . e($r['provincia']) . '</td>
    <td>' . e($r['fecha_ingreso']) . '</td>
    <td>' . e($r['edad']) . '</td>
    <td>' . e($r['estadia']) . '</td>
    <td>' . e($r['ciudad_acogida']) . '</td>
    <td>' . e($r['municipio_acogida']) . '</td>
    <td>' . e($r['tipologia_acogida']) . '</td>
    <td class="text-center">' . e($r['juzgado']) . '</td>
    <td class="text-center">' . e($r['documento']) . '</td>
    <td>' . e($r['unidad_educativa']) . '</td>
    <td class="text-center">' . e($r['curso']) . '</td>
    <td>' . e($r['grado']) . '</td>
</tr>';
        }

        $html .= '</tbody></table>';

        $pdf = Pdf::loadHTML($html)->setPaper('letter', 'landscape');

        return $pdf->stream('reporte_residentes.pdf');
    }
}

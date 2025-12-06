<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Gestion;

class GestionEscolarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $gestiones=Gestion::all();
        return view('TSocial.Escolaridad.CreaGestionEscolar')->with(['gestiones'=>$gestiones]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
{
     $residentes = collect();
    // Validación
    $request->validate([
        'gestion' => 'required|exists:gestiones,id',
    ]);

    // Comprobar si ya existe una relación/registro con esa gestión
    $existe = DB::table('grado_residente_unidad_educativa')
            ->where('gestion_id', $request->gestion)
            ->exists();

    if ($existe) {
        return back()->with('error', 'La gestión seleccionada ya existe.');
    }

    $penultimoGestion = DB::table('gestiones')
                        ->orderBy('id', 'desc')
                        ->skip(1)   // salta el último
                        ->take(1)   // toma el penúltimo
                        ->value('id');

// Extraer los datos de la tabla grado_residente_unidad_educativa
$registros = DB::table('grado_residente_unidad_educativa')
                ->select('residente_id', 'ue_id', 'curso_id', 'grado_id', 'gestion_id')
                ->where('gestion_id', $penultimoGestion)
                ->get();

                foreach ($registros as $registro) {
                   
                    //pasa de prekinder a kinder
                    if($registro->grado_id==1 && $registro->curso_id==1){
                        $grado_id=1;
                        $curso_id=2; 

                        DB::table('grado_residente_unidad_educativa')->insert([
                                'residente_id' => $registro->residente_id,
                                'ue_id'        => $registro->ue_id,
                                'grado_id'     => $grado_id,
                                'curso_id'     => $curso_id,
                                'gestion_id'   => $request->gestion, 
                         ]);
                    }elseif($registro->grado_id==1 && $registro->curso_id==2){
                        //pasa de kinder a primero de primaria
                        $grado_id=2;
                        $curso_id=3; 
                         DB::table('grado_residente_unidad_educativa')->insert([
                                'residente_id' => $registro->residente_id,
                                'ue_id'        => $registro->ue_id,
                                'grado_id'     => $grado_id,
                                'curso_id'     => $curso_id,
                                'gestion_id'   => $request->gestion, 
                         ]);
                        
                    }elseif($registro->grado_id==2 && $registro->curso_id==3){
                        //pasa de primero a segundo de primaria
                        $grado_id=2;
                        $curso_id=4; 
                         DB::table('grado_residente_unidad_educativa')->insert([
                                'residente_id' => $registro->residente_id,
                                'ue_id'        => $registro->ue_id,
                                'grado_id'     => $grado_id,
                                'curso_id'     => $curso_id,
                                'gestion_id'   => $request->gestion, 
                         ]);
                    }elseif($registro->grado_id==2 && $registro->curso_id==4){
                        //pasa de segundo a tercero de primaria
                        $grado_id=2;
                        $curso_id=5; 
                         DB::table('grado_residente_unidad_educativa')->insert([
                                'residente_id' => $registro->residente_id,
                                'ue_id'        => $registro->ue_id,
                                'grado_id'     => $grado_id,
                                'curso_id'     => $curso_id,
                                'gestion_id'   => $request->gestion, 
                         ]);

                    }elseif($registro->grado_id==2 && $registro->curso_id==5){
                        //pasa de tercero a cuarto de primaria
                        $grado_id=2;
                        $curso_id=6; 
                         DB::table('grado_residente_unidad_educativa')->insert([
                                'residente_id' => $registro->residente_id,
                                'ue_id'        => $registro->ue_id,
                                'grado_id'     => $grado_id,
                                'curso_id'     => $curso_id,
                                'gestion_id'   => $request->gestion, 
                         ]);

                    }elseif($registro->grado_id==2 && $registro->curso_id==6){
                        //pasa de cuarto a quinto de primaria
                        $grado_id=2;
                        $curso_id=7; 
                         DB::table('grado_residente_unidad_educativa')->insert([
                                'residente_id' => $registro->residente_id,
                                'ue_id'        => $registro->ue_id,
                                'grado_id'     => $grado_id,
                                'curso_id'     => $curso_id,
                                'gestion_id'   => $request->gestion, 
                         ]);

                    }elseif($registro->grado_id==2 && $registro->curso_id==7){
                        //pasa de quinto a sexto de primaria
                        $grado_id=2;
                        $curso_id=8; 
                         DB::table('grado_residente_unidad_educativa')->insert([
                                'residente_id' => $registro->residente_id,
                                'ue_id'        => $registro->ue_id,
                                'grado_id'     => $grado_id,
                                'curso_id'     => $curso_id,
                                'gestion_id'   => $request->gestion, 
                         ]);

                    }elseif($registro->grado_id==2 && $registro->curso_id==8){
                        //pasa de sexto de primaria a primero de secundaria
                        $grado_id=3;
                        $curso_id=3; 
                         DB::table('grado_residente_unidad_educativa')->insert([
                                'residente_id' => $registro->residente_id,
                                'ue_id'        => $registro->ue_id,
                                'grado_id'     => $grado_id,
                                'curso_id'     => $curso_id,
                                'gestion_id'   => $request->gestion, 
                         ]);
                    }elseif($registro->grado_id==3 && $registro->curso_id==3){
                        //pasa de primero de secundaria a segundo secundaria
                        $grado_id=3;
                        $curso_id=4; 
                         DB::table('grado_residente_unidad_educativa')->insert([
                                'residente_id' => $registro->residente_id,
                                'ue_id'        => $registro->ue_id,
                                'grado_id'     => $grado_id,
                                'curso_id'     => $curso_id,
                                'gestion_id'   => $request->gestion, 
                         ]);
                    }elseif($registro->grado_id==3 && $registro->curso_id==4){
                        //pasa de segundo de secundaria a tercero de secundaria
                        $grado_id=3;
                        $curso_id=5; 
                         DB::table('grado_residente_unidad_educativa')->insert([
                                'residente_id' => $registro->residente_id,
                                'ue_id'        => $registro->ue_id,
                                'grado_id'     => $grado_id,
                                'curso_id'     => $curso_id,
                                'gestion_id'   => $request->gestion, 
                         ]);
                    }elseif($registro->grado_id==3 && $registro->curso_id==5){
                        //pasa de tercero de secundaria a cuarto de secundaria
                        $grado_id=3;
                        $curso_id=6; 
                         DB::table('grado_residente_unidad_educativa')->insert([
                                'residente_id' => $registro->residente_id,
                                'ue_id'        => $registro->ue_id,
                                'grado_id'     => $grado_id,
                                'curso_id'     => $curso_id,
                                'gestion_id'   => $request->gestion, 
                         ]);
                    }elseif($registro->grado_id==3 && $registro->curso_id==6){
                        //pasa de cuarto  de secundaria a quinto de secundaria
                        $grado_id=3;
                        $curso_id=7; 
                         DB::table('grado_residente_unidad_educativa')->insert([
                                'residente_id' => $registro->residente_id,
                                'ue_id'        => $registro->ue_id,
                                'grado_id'     => $grado_id,
                                'curso_id'     => $curso_id,
                                'gestion_id'   => $request->gestion, 
                         ]);

                    }elseif($registro->grado_id==3 && $registro->curso_id==7){
                        //pasa de Quinto  de secundaria a sexto de secundaria
                        $grado_id=3;
                        $curso_id=8; 
                         DB::table('grado_residente_unidad_educativa')->insert([
                                'residente_id' => $registro->residente_id,
                                'ue_id'        => $registro->ue_id,
                                'grado_id'     => $grado_id,
                                'curso_id'     => $curso_id,
                                'gestion_id'   => $request->gestion, 
                         ]);

                    }elseif($registro->grado_id==3 && $registro->curso_id==8){
                       
                         
                          $residentes->push($registro->residente_id);
                       
                    }
                        
                }//end foreach

    // Supongamos que ya llenaste la Collection $residentes
// Verificar si hay residentes
if ($residentes->isNotEmpty()) {

    // Obtener nombres y apellidos de la base de datos
    $listaResidentess = DB::table('residentes')
        ->whereIn('id', $residentes)
        ->select('nombre', 'apellido')
        ->get();

    // Solo construir la tabla si hay registros
    $residentesHtml = '';
    if ($listaResidentess->isNotEmpty()) {
        $residentesHtml .= '<table style="width:100%; text-align:left; border-collapse: collapse;">';
        $residentesHtml .= '<thead><tr><th>Nombre</th><th>Apellido</th></tr></thead>';
        $residentesHtml .= '<tbody>';

        foreach ($listaResidentess as $r) {
            $residentesHtml .= "<tr>";
            $residentesHtml .= "<td style='border:1px solid #ddd; padding:5px'>{$r->nombre}</td>";
            $residentesHtml .= "<td style='border:1px solid #ddd; padding:5px'>{$r->apellido}</td>";
            $residentesHtml .= "</tr>";
        }

        $residentesHtml .= '</tbody></table>';
    }

    // Enviar datos a la vista
    return back()->with([
        'gestionCreada'   => true,
        'residentesHtml'  => $residentesHtml
    ]);

} else {
    // No hay residentes, solo enviar el mensaje de gestión creada
    return back()->with([
        'gestionCreada' => true
    ]);
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
}

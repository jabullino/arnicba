<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AcogidaCircunstancial;
use Illuminate\Http\Request;
use App\Models\Ciudad;
use App\Models\Residente;
use App\Models\Extension;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Laravel\Facades\Image;
use App\Models\Tipologia;
use Illuminate\Support\Facades\DB;
use App\Models\Provincia;
use App\Models\Municipio;

class ResidenteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $residentes = Residente::all();
        return view('TSocial.Residente.ListaResidente')->with(['residentes' => $residentes]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $ciudades = Ciudad::all();
        $extensiones = Extension::all();
        $tipologias = Tipologia::all();

        return view('TSocial.Residente.RegistraResidente')->with(['ciudades' => $ciudades, 'extensiones' => $extensiones, 'tipologias' => $tipologias]);
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    // Validación de los campos
    $rules = [
        'nombre' => 'required|string|max:100',
        'apellido' => 'required|string|max:100',
        'fecnac' => 'nullable|date',
        'ci' => 'nullable|string|max:20|unique:residentes,ci',
        'extension' => 'nullable|string|max:10',
        'ciudad' => 'nullable|integer|exists:ciudades,id',
        'provincia' => 'nullable|integer|exists:provincias,id',
        'fec_ingreso' => 'required|date',
        'fec_egreso' => 'nullable|date|after_or_equal:fec_ingreso',
        'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:15000', // 🔥 2MB
        'fechadoc' => 'required|date',
        'numdoc' => 'required',
        'tipologia' => 'required',
        'ciudad_acogida' => 'required',
        'municipios_acogida' => 'required',
        'firma' => 'required',
    ];

    if (!$request->boolean('transferencia')) {
        $rules['fechadoc'] .= '|after_or_equal:fec_ingreso';
    }

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    try {
        $fotoPath = null;

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $ext = strtolower($file->getClientOriginalExtension());
            $nombreArchivo = time() . '_' . $file->getClientOriginalName();
            $carpetaDestino = storage_path('app/public/fotos_residentes');

            if (!file_exists($carpetaDestino)) {
                mkdir($carpetaDestino, 0755, true);
            }

            $rutaDestino = $carpetaDestino . '/' . $nombreArchivo;

            // Crear imagen según el tipo
            switch ($ext) {
                case 'jpg':
                case 'jpeg':
                    $src = imagecreatefromjpeg($file->getRealPath());

                    if (function_exists('exif_read_data')) {
                        $exif = @exif_read_data($file->getRealPath());
                        if (!empty($exif['Orientation'])) {
                            switch ($exif['Orientation']) {
                                case 3:
                                    $src = imagerotate($src, 180, 0);
                                    break;
                                case 6:
                                    $src = imagerotate($src, -90, 0);
                                    break;
                                case 8:
                                    $src = imagerotate($src, 90, 0);
                                    break;
                            }
                        }
                    }
                    break;

                case 'png':
                    $src = imagecreatefrompng($file->getRealPath());
                    break;

                default:
                    throw new \Exception('Formato de imagen no soportado.');
            }

            // Obtener tamaño original
            [$width, $height] = getimagesize($file->getRealPath());

            // 🔥 CAMBIO: mejor resolución
            $maxDim = 1200;

            $ratio = $width / $height;
            if ($width > $maxDim || $height > $maxDim) {
                if ($ratio > 1) {
                    $newWidth = $maxDim;
                    $newHeight = $maxDim / $ratio;
                } else {
                    $newHeight = $maxDim;
                    $newWidth = $maxDim * $ratio;
                }
            } else {
                $newWidth = $width;
                $newHeight = $height;
            }

            $dst = imagecreatetruecolor($newWidth, $newHeight);

            if ($ext == 'png') {
                imagealphablending($dst, false);
                imagesavealpha($dst, true);
            }

            imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

            // 🔥 CAMBIO IMPORTANTE: compresión dinámica SOLO para JPG
            if ($ext === 'jpg' || $ext === 'jpeg') {

                $quality = 85;

                do {
                    ob_start();
                    imagejpeg($dst, null, $quality);
                    $data = ob_get_clean();

                    $size = strlen($data);
                    $quality -= 5;

                } while ($size > 2 * 1024 * 1024 && $quality > 10);

                file_put_contents($rutaDestino, $data);

            } else {
                // PNG se mantiene igual
                imagepng($dst, $rutaDestino);
            }

            imagedestroy($src);
            imagedestroy($dst);

            $fotoPath = 'fotos_residentes/' . $nombreArchivo;
        }

        DB::beginTransaction();

        $residente = new Residente();
        $residente->nombre = strtoupper($request->nombre);
        $residente->apellido = strtoupper($request->apellido);
        $residente->fecnac = $request->fecnac;
        $residente->ci = $request->ci;
        $residente->ext = $request->extension;
        $residente->ciudad = $request->ciudad;
        $residente->provincia = $request->provincia;
        $residente->fec_ingreso = $request->fec_ingreso;
        $residente->fec_egreso = $request->fec_egreso;
        $residente->foto = $fotoPath;
        $residente->created_at = null;
        $residente->updated_at = null;
        $residente->deleted_at = null;

        $residente->save();
        $residente_id = Residente::latest('id')->first()->id;

        AcogidaCircunstancial::create([
            'residente_id' => $residente_id,
            'fecha' => $request->fechadoc,
            'numdoc' => $request->numdoc,
            'tipologia' => $request->tipologia,
            'ciudad' => $request->ciudad_acogida,
            'municipio' => $request->municipios_acogida,
            'firmante' => $request->firma,
        ]);

        DB::commit();

        return redirect()->back()->with('success', 'Residente registrado exitosamente.');

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()
            ->back()
            ->with('error', 'Error al registrar el residente: ' . $e->getMessage());
    }
}

    /**
     * Display the specified resource.
     */
   public function show($id)
{
    $residente = Residente::find($id);
    $acogida = AcogidaCircunstancial::where('residente_id', $id)->first();
    $extension = Extension::where('id', $residente->ext)->value('nombre');
    $ciudad = Ciudad::where('id', $residente->ciudad)->value('nombre');
    $provincia = Provincia::where('id', $residente->provincia)->value('nombre');
    $ciudad_acogida = Ciudad::where('id', $acogida->ciudad)->value('nombre');
    $municipio = Municipio::where('id', $acogida->municipio)->value('nombre');
    $tipologia = Tipologia::where('id', $acogida->tipologia)->value('nombre');

    // Calcular edad en años y meses (enteros)
    $edad = null;
    if ($residente->fecnac) {
        $fechaNacimiento = \Carbon\Carbon::parse($residente->fecnac);
        $ahora = \Carbon\Carbon::now();

        // Años completos
        $anios = $fechaNacimiento->diffInYears($ahora);

        // Fecha del último cumpleaños
        $ultimoCumple = $fechaNacimiento->copy()->addYears($anios);

        // Meses restantes
        $meses = $ultimoCumple->diffInMonths($ahora);

        $edad = intval($anios) . " años y " . intval($meses) . " meses";
    }

    // Calcular estadía en años, meses y días (enteros)
    $estadia = null;
    if ($residente->fec_ingreso) {
        $fecIngreso = \Carbon\Carbon::parse($residente->fec_ingreso);
        $ahora = \Carbon\Carbon::now();

        // Años completos
        $aniosEstadia = $fecIngreso->diffInYears($ahora);
        $ultimoAnio = $fecIngreso->copy()->addYears($aniosEstadia);

        // Meses completos después de los años
        $mesesEstadia = $ultimoAnio->diffInMonths($ahora);
        $ultimoMes = $ultimoAnio->copy()->addMonths($mesesEstadia);

        // Días completos después de los meses
        $diasEstadia = $ultimoMes->diffInDays($ahora);

        if ($aniosEstadia > 0) {
            $estadia = intval($aniosEstadia) . " años, " . intval($mesesEstadia) . " meses, " . intval($diasEstadia) . " días";
        } else {
            $estadia = intval($mesesEstadia) . " meses, " . intval($diasEstadia) . " días";
        }
    }

    return view('TSocial.Residente.MuestraResidente', compact(
        'residente',
        'acogida',
        'extension',
        'ciudad',
        'provincia',
        'ciudad_acogida',
        'municipio',
        'tipologia',
        'edad',
        'estadia'
    ));
}


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $residente = Residente::findOrFail($id);
        $acogida = AcogidaCircunstancial::where('residente_id', $residente->id)->first();

        // Listas para selects
        $ciudades = Ciudad::all();
        $provincias = $residente->ciudad ? Provincia::where('ciudad_id', $residente->ciudad)->get() : collect();
        $municipiosAcogida = $acogida ? Municipio::where('ciudad_id', $acogida->ciudad)->get() : collect();

        $extensiones = Extension::all();
        $tipologias = Tipologia::all();

        return view('TSocial.Residente.EditaResidente', compact(
            'residente',
            'acogida',
            'ciudades',
            'provincias',
            'municipiosAcogida',
            'extensiones',
            'tipologias'
        ));
    }



    /**
     * Update the specified resource in storage.
     */
 public function update(Request $request, $id)
{
    $residente = Residente::findOrFail($id);

    // Validación de los campos
    $rules = [
        'nombre' => 'required|string|max:100',
        'apellido' => 'required|string|max:100',
        'fecnac' => 'nullable|date',
        'ci' => 'nullable|string|max:20|unique:residentes,ci,' . $id,
        'extension' => 'nullable|string|max:10',
        'ciudad' => 'nullable|integer|exists:ciudades,id',
        'provincia' => 'nullable|integer|exists:provincias,id',
        'fec_ingreso' => 'required|date',
        'fec_egreso' => 'nullable|date|after_or_equal:fec_ingreso',
        'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:15000', // 🔥 2MB
        'fechadoc' => 'required|date',
        'numdoc' => 'required',
        'tipologia' => 'required',
        'ciudad_acogida' => 'required',
        'municipios_acogida' => 'required',
        'firma' => 'required',
    ];

    if (!$request->boolean('transferencia')) {
        $rules['fechadoc'] .= '|after_or_equal:fec_ingreso';
    }

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    try {
        DB::beginTransaction();

        // Actualizar datos
        $residente->nombre = strtoupper($request->nombre);
        $residente->apellido = strtoupper($request->apellido);
        $residente->fecnac = $request->fecnac;
        $residente->ci = $request->ci;
        $residente->ext = $request->extension;
        $residente->ciudad = $request->ciudad;
        $residente->provincia = $request->provincia;
        $residente->fec_ingreso = $request->fec_ingreso;
        $residente->fec_egreso = $request->fec_egreso;

        // FOTO
        if ($request->hasFile('foto')) {

            // Eliminar anterior
            if ($residente->foto && file_exists(storage_path('app/public/' . $residente->foto))) {
                unlink(storage_path('app/public/' . $residente->foto));
            }

            $file = $request->file('foto');
            $ext = strtolower($file->getClientOriginalExtension());
            $nombreArchivo = time() . '_' . $file->getClientOriginalName();
            $carpetaDestino = storage_path('app/public/fotos_residentes');

            if (!file_exists($carpetaDestino)) {
                mkdir($carpetaDestino, 0755, true);
            }

            $rutaDestino = $carpetaDestino . '/' . $nombreArchivo;

            // Crear imagen
            switch ($ext) {
                case 'jpg':
                case 'jpeg':
                    $src = imagecreatefromjpeg($file->getRealPath());

                    if (function_exists('exif_read_data')) {
                        $exif = @exif_read_data($file->getRealPath());
                        if (!empty($exif['Orientation'])) {
                            switch ($exif['Orientation']) {
                                case 3:
                                    $src = imagerotate($src, 180, 0);
                                    break;
                                case 6:
                                    $src = imagerotate($src, -90, 0);
                                    break;
                                case 8:
                                    $src = imagerotate($src, 90, 0);
                                    break;
                            }
                        }
                    }
                    break;

                case 'png':
                    $src = imagecreatefrompng($file->getRealPath());
                    break;

                default:
                    throw new \Exception('Formato de imagen no soportado.');
            }

            // 🔥 CAMBIO: mejor resolución
            [$width, $height] = getimagesize($file->getRealPath());
            $maxDim = 1200;

            $ratio = $width / $height;

            if ($width > $maxDim || $height > $maxDim) {
                if ($ratio > 1) {
                    $newWidth = $maxDim;
                    $newHeight = $maxDim / $ratio;
                } else {
                    $newHeight = $maxDim;
                    $newWidth = $maxDim * $ratio;
                }
            } else {
                $newWidth = $width;
                $newHeight = $height;
            }

            $dst = imagecreatetruecolor($newWidth, $newHeight);

            if ($ext == 'png') {
                imagealphablending($dst, false);
                imagesavealpha($dst, true);
            }

            imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

            // 🔥 CAMBIO: compresión inteligente
            if ($ext === 'jpg' || $ext === 'jpeg') {

                $quality = 85;

                do {
                    ob_start();
                    imagejpeg($dst, null, $quality);
                    $data = ob_get_clean();

                    $size = strlen($data);
                    $quality -= 5;

                } while ($size > 2 * 1024 * 1024 && $quality > 10);

                file_put_contents($rutaDestino, $data);

            } else {
                imagepng($dst, $rutaDestino);
            }

            imagedestroy($src);
            imagedestroy($dst);

            $residente->foto = 'fotos_residentes/' . $nombreArchivo;
        }

        $residente->save();

        AcogidaCircunstancial::updateOrCreate(
            ['residente_id' => $residente->id],
            [
                'fecha' => $request->fechadoc,
                'numdoc' => $request->numdoc,
                'ciudad' => $request->ciudad_acogida,
                'municipio' => $request->municipios_acogida,
                'tipologia' => $request->tipologia,
                'firmante' => $request->firma,
            ]
        );

        DB::commit();

        return redirect()->route('residentes.index')
            ->with('success', 'Residente actualizado correctamente.');

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()
            ->back()
            ->with('error', 'Error al actualizar el residente: ' . $e->getMessage());
    }
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Buscar el residente por ID
        $residente = Residente::findOrFail($id);

        // Opcional: si quieres eliminar la foto asociada
        if ($residente->foto && file_exists(storage_path('app/public/fotos_residentes/' . basename($residente->foto)))) {
            unlink(storage_path('app/public/fotos_residentes/' . basename($residente->foto)));
        }

        // Eliminar el residente
        $residente->delete();

        // Redirigir a la lista de residentes con mensaje de éxito
        return redirect()->route('residentes.index')
            ->with('success', 'Residente eliminado correctamente.');
    }
}

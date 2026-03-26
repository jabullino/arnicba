<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Extension;
use App\Models\Ciudad;
use App\Models\Cargo;
use App\Models\Provincia;
use App\Models\Documento;
use Yajra\DataTables\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class UsuariosController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $usuarios = User::all();
        $cargos = new Cargo();
        $cont = 0;
        return view('AdminSis.Usuarios')->with(['usuarios' => $usuarios, 'cargos' => $cargos, 'cont' => $cont]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $extensiones = Extension::all();
        $ciudades = Ciudad::all();
        $cargos = Cargo::all();
        $documentos = Documento::orderBy('nombre', 'asc')->get();
        return view('AdminSis.CreaUsuario')->with(['extensiones' => $extensiones, 'ciudades' => $ciudades, 'cargos' => $cargos, 'documentos' => $documentos]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|alpha_spaces|max:255',
            'apellido' => 'required|alpha_spaces|max:255',
            'ci' => 'required|string|max:20',
            'extension' => 'required',
            'fecnac' => 'required|date',
            'ciudad' => 'required',
            'provincia' => 'required',
            'direccion' => 'required|string|max:100',
            'referencias' => 'required|string|max:100',
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
            'telefono' => 'required|string|max:20',
            'email' => 'required|email|max:255|unique:users',

            // Reglas del password
            'password' => [
                'required',
                'confirmed',
                'string',
                'min:8',
                'regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/'
            ],

            'password_confirmation' => 'required|same:password|min:8',
            'cargo' => 'required',
            'fecingreso' => 'required|date',
            'foto' => 'image|mimes:jpeg,png,jpg,gif|max:1000'
        ]);


        // Si la validación falla, redirige con los errores
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            DB::beginTransaction();
            $nom = substr($request->nombre, 0, 3);
            $ape = substr($request->apellido, 0, 3);
            $nomfinal = $nom . $ape;
            if ($request->has('foto')) {
                $file = $request->file('foto');
                $nombrePersonalizado = Str::slug($nomfinal) . '.' . $file->getClientOriginalExtension();
                $ruta = $file->storeAs('fotos', $nombrePersonalizado, 'public');
            } else {
                $ruta = 'Foto no escogida';
            }
            $user = User::create([
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'ci' => $request->ci,
                'extension_id' => $request->extension,
                'fecnac' => $request->fecnac,
                'ciudad_id' => $request->ciudad,
                'provincia_id' => $request->provincia,
                'direccion' => $request->direccion,
                'referencias' => $request->referencias,
                'telefono' => $request->telefono,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'cargo_id' => $request->cargo,
                'fec_ingreso' => $request->fecingreso,
                'fec_egreso' => Carbon::today()->toDateString(),
                'rutafoto' => $ruta,
                'latitud' => $request->latitud,
                'longitud' => $request->longitud,

            ]);
            $cargos = new Cargo();
            $usuarios = User::all();
            $cont = 0;
            session()->flash('success', '¡Usuario creado exitosamente!');
            DB::commit();
            return view('AdminSis.Usuarios')->with(['usuarios' => $usuarios, 'cont' => $cont, 'cargos' => $cargos]);
        } catch (QueryException $e) {
            DB::rollBack();
            return back()->with('error', 'No se pudo crear el usuario' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
public function show(string $id)
{
    $usr = User::findOrFail($id);

    $extensiones = Extension::all();
    $ciudades = Ciudad::all();
    $cargos = Cargo::all();

    $ext = new Extension();
    $ciu = new Ciudad();
    $car = new Cargo();
    $prov = new Provincia();

    return view('AdminSis.MuestraUsuario', compact(
        'usr',
        'extensiones',
        'ciudades',
        'cargos',
        'ext',
        'ciu',
        'car',
        'prov'
    ));
}
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $usr = User::find($id);
        $extensiones = Extension::all();
        $ciudades = Ciudad::all();
        $cargos = Cargo::all();
        $documentos = Documento::all();
        $ext = new Extension();
        $ciu = new Ciudad();
        $car = new Cargo();
        $prov = new Provincia();
        // $documentosSeleccionados = $usr->documentos->pluck('id')->toArray();

        return view('AdminSis.EditaUsuario')->with(['usr' => $usr, 'extensiones' => $extensiones, 'ciudades' => $ciudades, 'cargos' => $cargos, 'ext' => $ext, 'ciu' => $ciu, 'car' => $car, 'prov' => $prov]);
    }

    /**
     * Update the specified resource in storage.
     */
      public function update(Request $request, string $id)
{
    $validator = Validator::make($request->all(), [
        'nombre' => 'required|alpha_spaces|max:255',
        'apellido' => 'required|alpha_spaces|max:255',
        'ci' => 'required|string|max:20',
        'extension' => 'required',
        'fecnac' => 'required|date',
        'ciudad' => 'required',
        'provincia' => 'required',
        'direccion' => 'required|string|max:100',
        'referencias' => 'required|string|max:100',
        'telefono' => 'required|string|max:20',

        // ✅ CORREGIDO AQUÍ
        'email' => 'required|email|max:255|unique:users,email,' . $id,

        'password' => [
            'nullable', // 🔥 mejor que required en update
            'confirmed',
            'string',
            'min:8',
            'regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/'
        ],

        'password_confirmation' => 'nullable|same:password|min:8',
        'cargo' => 'required',
        'fecingreso' => 'required|date',
        'foto' => 'image|mimes:jpeg,png,jpg,gif|max:1000'
    ]);

    DB::beginTransaction();

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    try {
        $user = User::find($id);

        $user->nombre = $request->nombre;
        $user->apellido = $request->apellido;
        $user->ci = $request->ci;
        $user->extension_id = $request->extension;
        $user->fecnac = $request->fecnac;
        $user->ciudad_id = $request->ciudad;
        $user->provincia_id = $request->provincia;
        $user->direccion = $request->direccion;
        $user->referencias = $request->referencias;
        $user->telefono = $request->telefono;

        // Ya no necesitas comparar
        $user->email = $request->email;

        // ✅ Solo actualiza password si se envía
        if (!empty($request->password)) {
            $user->password = Hash::make($request->password);
        }

        if ($request->has('fecegreso')) {
            $user->fec_egreso = $request->fecegreso;
        }

        if ($request->hasFile('foto')) {
            $nom = substr($request->nombre, 0, 3);
            $ape = substr($request->apellido, 0, 3);
            $nomfinal = $nom . $ape;

            $file = $request->file('foto');
            $nombrePersonalizado = Str::slug($nomfinal) . '.' . $file->getClientOriginalExtension();
            $ruta = $file->storeAs('fotos', $nombrePersonalizado, 'public');

            $user->foto = $ruta; // ⚠️ asegúrate de guardar la ruta
        }

        $user->cargo_id = $request->cargo;
        $user->save();

        DB::commit();

        session()->flash('success', '¡Usuario Editado exitosamente!');
        return redirect()->route('Usuarios.index');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'No se pudo editar el usuario: ' . $e->getMessage());
    }
}
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        try {
            DB::beginTransaction();
            $cantuseradmin = User::where('id', '1')->count();
            if (($cantuseradmin == 1) && ($id == 1)) {
                $extensiones = Extension::all();
                $ciudades = Ciudad::all();
                $cargos = Cargo::all();
                session()->flash('error', 'Antes de eliminar el único usuario Administrador de Sistema debe crear uno nuevo');
                return view('AdminSis.UserAdminSis')->with(['extensiones' => $extensiones, 'ciudades' => $ciudades, 'cargos' => $cargos]);
            };
            $usr = User::find($id);
            $usr->delete();
            $users = User::all();
            DB::commit();
            return redirect()->route('Usuarios.index')->with(['success', 'Usuario Eliminado Exitosamente', 'usuarios', $users]);
        } catch (QueryException $e) {
            DB::rollBack();
            return back()->with('error', 'No se pudo eliminar al usuario' . $e->getMessage());
        }
    }
}

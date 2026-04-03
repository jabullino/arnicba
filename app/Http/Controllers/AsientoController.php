<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asiento;
use App\Models\Cuenta;
use App\Models\SubCuenta;
use App\Models\TipoMovimiento;
use App\Models\TipoCambioCompra;
use App\Models\TipoCambioVenta;
use App\Models\OrigenFondos;
use App\Models\Gestion;
use App\Models\Proyecto;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AsientosExport;
use PDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class AsientoController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $numasiento = Asiento::all()->count();
        $tipocambiocompra = TipoCambioCompra::latest()->value('tc');
        $tipocambioventa = TipoCambioVenta::latest()->value('tv');
        $tipomovimiento = TipoMovimiento::all();
        $origenfondos = OrigenFondos::all();
        $cuentas = Cuenta::all();
        $cuentasaux = new Cuenta();
        $subcuentasaux = new SubCuenta();

        if ($numasiento < 1) {
            return view('Administrador.FormCreaAsiento')->with(['numinterno' => '1', 'tc' => $tipocambiocompra, 'tv' => $tipocambioventa, 'tipomovimiento' => $tipomovimiento, 'cuentas' => $cuentas, 'origenfondos' => $origenfondos]);
        } else {
            $numasiento = Asiento::where('estado_id', 1)->latest()->value('id');
            $tipocambiocompra = TipoCambioCompra::latest()->value('tc');
            $tipocambioventa = TipoCambioVenta::latest()->value('tv');
            $tipomovimiento = TipoMovimiento::all();
            $origenfondos = OrigenFondos::all();
            $cuentas = Cuenta::all();
            $asientos = Asiento::orderBy('id', 'desc')->paginate(10);
            $asiento = new Asiento();
            $cuentasaux = new Cuenta();
            $subcuentasaux = new SubCuenta();
            //$asientos=Asiento::where('estado_id',1)->get();            
            return view('Administrador.FormAsientos')->with(['numinterno' => $numasiento + 1, 'tc' => $tipocambiocompra, 'tv' => $tipocambioventa, 'tipomovimiento' => $tipomovimiento, 'cuentas' => $cuentas, 'cuentasaux' => $cuentasaux, 'subcuentasaux' => $subcuentasaux, 'origenfondos' => $origenfondos, 'asientos' => $asientos, 'asiento' => $asiento, 'cuentasaux' => $cuentasaux, 'subcuentasaux' => $subcuentasaux]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $numasiento = Asiento::where('estado_id', 1)->latest()->value('id');
        $tipocambiocompra = TipoCambioCompra::latest()->value('tc');
        $tipocambioventa = TipoCambioVenta::latest()->value('tv');
        $tipomovimiento = TipoMovimiento::all();
        $origenfondos = OrigenFondos::all();
        $cuentas = Cuenta::all();
        return view('Administrador.FormCreaAsiento')->with(['numinterno' => $numasiento + 1, 'tc' => $tipocambiocompra, 'tv' => $tipocambioventa, 'tipomovimiento' => $tipomovimiento, 'cuentas' => $cuentas, 'origenfondos' => $origenfondos]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $tipocambiocompra_id = TipoCambiocompra::where('tc', $request->tc)->value('id');
        $tipocambioventa_id = TipoCambioVenta::where('tv', $request->tv)->value('id');
        $gestion_id = Gestion::latest()->value('id');
        $proyecto_id = null;
        $estado_id = 1;


        $validator = Validator::make($request->all(), [
            'numinterno' => 'required|numeric',
            'fecha' => 'required|date|before_or_equal:today',
            'tc' => 'required',
            'regex:/^\d{1,3}(?:,\d{3})*(?:\.\d{2})$/',
            'tipomovimiento' => 'required|string',
            'factura' => 'required_without:recibo',
            'recibo' => 'required_without:factura',
            'cuenta' => 'required|string',
            'subcuenta' => 'required|string',
            'importebs' => 'required|regex:/^\d{1,8}(\.\d{1,2})?$/',
            'importesus' => 'required|regex:/^\d{1,8}(\.\d{1,2})?$/',
            'origenfondos' => 'required|string',

        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            DB::beginTransaction();
            Asiento::create([
                'gestion_id' => Gestion::where('estado_id', '1')->latest()->value('id'),
                'fec_asiento' => $request->fecha,
                'tc_id' => $tipocambiocompra_id,
                'tv_id' => $tipocambioventa_id,
                'recibo' => $request->recibo,
                'factura' => $request->factura,
                'cuenta' => $request->cuenta,
                'sub_cuenta' => $request->subcuenta,
                'monto_bs' => $request->importebs,
                'monto_sus' => $request->importesus,
                'origenfondos_id' => $request->origenfondos,
                'tipomovimiento_id' => $request->tipomovimiento,
                'proyecto_id' => null,
                'estado_id' => 1,
            ]);
            
            session()->flash('success', '¡Asiento creado exitosamente!');
            DB::commit();
            $numasiento = Asiento::where('estado_id', 1)->latest()->value('id');
            $tipocambiocompra = TipoCambioCompra::latest()->value('tc');
            $tipocambioventa = TipoCambioVenta::latest()->value('tv');
            $tipomovimiento = TipoMovimiento::all();
            $origenfondos = OrigenFondos::all();
            $cuentas = Cuenta::all();
            $asientos = Asiento::orderBy('id','desc')->paginate(10);
            $asiento = new Asiento();
            $cuentasaux = new Cuenta();
            $subcuentasaux = new SubCuenta();
            return view('Administrador.FormAsientos')->with(['numinterno' => $numasiento + 1, 'tc' => $tipocambiocompra, 'tv' => $tipocambioventa, 'tipomovimiento' => $tipomovimiento, 'cuentas' => $cuentas, 'origenfondos' => $origenfondos, 'asientos' => $asientos, 'asiento' => $asiento, 'cuentasaux' => $cuentasaux, 'subcuentasaux' => $subcuentasaux]);
        } catch (QueryException $e) {
            DB::rollBack();
            return back()->with('error', 'No se guardar el asiento de diario' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $asiento = Asiento::find($id);
        $numinterno = Asiento::where('id', $id);
        $tipocambiocompra = new TipoCambioCompra();
        $tipocambioventa = new TipoCambioVenta();
        $cuenta = new Cuenta();
        $subcuenta = new SubCuenta();
        $origenfondos = new OrigenFondos();
        $tipomovimiento = new TipoMovimiento();
        $cuentas = Cuenta::all();
        $cuentasaux = new Cuenta();
        $subcuentasaux = new SubCuenta();
        $subcuentas = SubCuenta::all();
        $origenesfondos = OrigenFondos::all();
        $tiposmovimientos = TipoMovimiento::all();

        return view('Administrador.FormMuestraAsiento')->with(['asiento' => $asiento, 'numinterno' => $numinterno, 'tipocambiocompra' => $tipocambiocompra, 'tipocambioventa' => $tipocambioventa, 'cuenta' => $cuenta, 'subcuenta' => $subcuenta, 'origenfondos' => $origenfondos, 'tipomovimiento' => $tipomovimiento, 'cuentas' => $cuentas, 'subcuentas' => $subcuentas, 'tiposmovimientos' => $tiposmovimientos, 'origenesfondos' => $origenesfondos, 'cuentasaux' => $cuentasaux, 'subcuentasaux' => $subcuentasaux]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        $asiento = Asiento::find($id);
        $numinterno = Asiento::where('id', $id);
        $tipocambiocompra = new TipoCambioCompra();
        $tipocambioventa = new TipoCambioVenta();
        $cuenta = new Cuenta();
        $subcuenta = new SubCuenta();
        $origenfondos = new OrigenFondos();
        $tipomovimiento = new TipoMovimiento();
        $cuentas = Cuenta::all();
        $subcuentas = SubCuenta::all();
        $origenesfondos = OrigenFondos::all();
        $tiposmovimientos = TipoMovimiento::all();

        return view('Administrador.FormEditaAsiento')->with(['asiento' => $asiento, 'numinterno' => $numinterno, 'tipocambiocompra' => $tipocambiocompra, 'tipocambioventa' => $tipocambioventa, 'cuenta' => $cuenta, 'subcuenta' => $subcuenta, 'origenfondos' => $origenfondos, 'tipomovimiento' => $tipomovimiento, 'cuentas' => $cuentas, 'subcuentas' => $subcuentas, 'tiposmovimientos' => $tiposmovimientos, 'origenesfondos' => $origenesfondos]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $asiento = Asiento::find($id);
        
        $validator = Validator::make($request->all(), [
            'numinterno' => 'required|numeric',
            'fecha' => 'required|date|before_or_equal:today',
            'tc' => 'required',
            'regex:/^\d{1,3}(?:,\d{3})*(?:\.\d{2})$/',
            'tv' => 'required',
            'regex:/^\d{1,3}(?:,\d{3})*(?:\.\d{2})$/',
            'tipomovimiento' => 'required|string',
            'factura' => 'required_without:recibo',
            'recibo' => 'required_without:factura',
            'cuenta' => 'required|string',
            'subcuenta' => 'required|string',
            'importebs' => ['required', 'regex:/^\d+(\.\d{1,2})?$/'],
            'importesus' => ['required', 'regex:/^\d+(\.\d{1,2})?$/'],
            'origenfondos' => 'required|string',

        ]);
         
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $tipocambiocompra = TipoCambioCompra::where('tc', $request->tc)->value('id');
        $tipocambioventa = TipoCambioVenta::where('tv', $request->tv)->value('id');
           
        try {
            DB::beginTransaction();
            $asiento->fec_asiento = $request->fecha;
            $asiento->tc_id = $tipocambiocompra;
            $asiento->tv_id = $tipocambioventa;
            $asiento->factura = $request->factura;
            $asiento->recibo = $request->recibo;
            $asiento->cuenta = $request->cuenta;
            $asiento->sub_cuenta = $request->subcuenta;
            $asiento->monto_bs = $request->importebs;
            $asiento->monto_sus = $request->importesus;
            $asiento->origenfondos_id = $request->origenfondos;
            $asiento->tipomovimiento_id = $request->tipomovimiento;
            $asiento->save();
            DB::commit();
            
            $numasiento = Asiento::where('estado_id', 1)->latest()->value('id');
            $tipocambiocompra = TipoCambioCompra::latest()->value('tc');
            $tipocambioventa = TipoCambioVenta::latest()->value('tv');
            $tipomovimiento = TipoMovimiento::all();
            $origenfondos = OrigenFondos::all();
            $cuentas = Cuenta::all();
            $asientos = Asiento::all();
            $asiento = new Asiento();
            $cuentasaux = new Cuenta;
            $subcuentasaux = new SubCuenta();
            $asientos = Asiento::orderBy('id','desc')->paginate(10);
            session()->flash('success', '¡Asiento Editado exitosamente!');
            return view('Administrador.FormAsientos')->with(['numinterno' => $numasiento + 1, 'tc' => $tipocambiocompra, 'tv' => $tipocambioventa, 'tipomovimiento' => $tipomovimiento, 'cuentas' => $cuentas, 'cuentasaux' => $cuentasaux, 'subcuentasaux' => $subcuentasaux, 'origenfondos' => $origenfondos, 'asientos' => $asientos, 'asiento' => $asiento]);
        } catch (QueryException $e) {
            DB::rollBack();
            return back()->with('error', 'No se pudo actualizar el asiento de diario' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    { 
        try {
            DB::beginTransaction();
            $asiento = Asiento::find($id);
            $asiento->delete();
            DB::commit();
            $asientos = Asiento::all();
            $cuentasaux = new Cuenta();
            $subcuentasaux = new SubCuenta();
            $asientos = Asiento::where('estado_id', 1)->get();
             session()->flash('success', '¡Asiento eliminado exitosamente!');
            return redirect()->route('Asientos.index')->with(['success', 'Asiento Eliminado Exitosamente', 'asientos', $asientos, 'cuentasaux' => $cuentasaux, 'subcuentasaux' => $subcuentasaux]);
        } catch (QueryException $e) {
            DB::rollBack();
            return back()->with('error', 'No se pudo eliminar el asiento de diario' . $e->getMessage());
        }
    }
}

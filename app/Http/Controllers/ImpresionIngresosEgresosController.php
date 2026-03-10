<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Ingreso;
use App\Models\DetalleIngreso;
use App\Models\Egreso;
use App\Models\EgresoDetalle;

class ImpresionIngresosEgresosController extends Controller
{

/*
======================================================
CABECERA INGRESO
======================================================
*/

public function imprimirCabeceraIngreso(
    $productoId,
    $fecha,
    $documento,
    $cantidad,
    $saldo,
    $vencimiento
){

    $producto = $this->obtenerNombre($productoId);
    $unidades = Producto::obtenerUnidadNombre($productoId);

    $pdf = new \TCPDF('P','mm',[195,215],true,'UTF-8',false);

    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

    $pdf->SetMargins(0,0,0);
    $pdf->SetAutoPageBreak(false,0);
    $pdf->AddPage();
    $pdf->SetFont('helvetica','',6.5);

    $pdf->SetXY(57.2,32);
    $pdf->Cell(0,5,'FUNDACION CENTRO DE ACOGIDA ARCA DE RESCATE',0,1);

    $pdf->SetXY(25,38);
    $pdf->Cell(0,5,'CENTRAL',0,1);

    $pdf->SetXY(125,38);
    $pdf->Cell(0,5,'LOCALIZACION: COCHABAMBA GRANDE-VILOMA',0,1);

    $pdf->SetXY(26,43);
    $pdf->Cell(28,5,'MATERIAL:',0,0);
    $pdf->Cell(100,5,strtoupper($producto['nombre']),0,0);

    $pdf->SetXY(130,43);
    $pdf->Cell(25,5,'UNIDAD:',0,0);
    $pdf->Cell(30,5,strtoupper($unidades),0,1);

    $pdf->SetY(58);

    $this->lineaIngreso(
        $pdf,
        $fecha,
        $documento,
        $cantidad,
        $saldo,
        $vencimiento
    );

    return $pdf->Output('kardex.pdf','I');
}

/*
======================================================
OBTENER NOMBRE PRODUCTO
======================================================
*/

public function obtenerNombre($id)
{
    $producto = Producto::findOrFail($id);

    return $producto->obtenerNombreProducto(
        $producto->id,
        $producto->categoria_id
    );
}

/*
======================================================
LINEA INGRESO
======================================================
*/

public function lineaIngreso(
    $pdf,
    $fecha,
    $factura,
    $entrada,
    $saldo,
    $fechaVencimiento = null
){

    $anchos = [21,17,14,5,12,17];

    $xBase = 10;
    $yBase = $pdf->GetY();

    $x1 = $xBase;
    $x2 = $x1 + $anchos[0];
    $x3 = $x2 + $anchos[1];
    $x4 = $x3 + $anchos[2];
    $x5 = $x4 + $anchos[3] + 12;
    $x6 = $x5 + $anchos[4] + 3;

    $pdf->Text($x1+3,$yBase,$fecha);
    $pdf->Text($x2+2,$yBase,$factura);

    $entradaTexto = number_format($entrada,2);

    $pdf->Text(
        $x3 + ($anchos[2] - $pdf->GetStringWidth($entradaTexto) - 1),
        $yBase,
        $entradaTexto
    );

    $saldoTexto = number_format($saldo,2);

    $pdf->Text(
        $x5 + ($anchos[4] - $pdf->GetStringWidth($saldoTexto) - 2),
        $yBase,
        $saldoTexto
    );

    $pdf->Text($x6,$yBase,$fechaVencimiento ?? '');
}

/*
======================================================
LINEA EGRESO
======================================================
*/

public function lineaEgreso($pdf,$fecha,$numrecibo,$cantidad,$saldo)
{

    $alto = 8;

    $anchos = [
        21,17,16,14,15,16,18,19,17,19,22
    ];

    $pdf->SetX(13);

    $pdf->Cell($anchos[0],$alto,$fecha,0,0,'C');
    $pdf->Cell($anchos[1],$alto,$numrecibo,0,0,'C');
    $pdf->Cell($anchos[2],$alto,'',0,0,'C');
    $pdf->Cell($anchos[3],$alto,number_format($cantidad,2),0,0,'C');
    $pdf->Cell($anchos[4],$alto,number_format($saldo,2),0,0,'C');

    for($i=5;$i<=10;$i++){
        $pdf->Cell($anchos[$i],$alto,'',0,0,'C');
    }
}

/*
======================================================
REVERSO INICIO (14-16)
======================================================
*/

private function posicionReversoInicio($lineas)
{
    $fila14 = 21;
    $altoCelda = 8;

    return $fila14 + (($lineas - 14) * $altoCelda);
}

/*
======================================================
REVERSO RESTO (17-32)
======================================================
*/

private function calcularPosicionReverso($lineas)
{

    $posiciones = [

        17 => 45,
        18 => 53,
        19 => 61,
        20 => 69,
        21 => 77,
        22 => 85,
        23 => 93,
        24 => 101,
        25 => 109,
        26 => 117,
        27 => 125,
        28 => 133,
        29 => 141,
        30 => 149,
        31 => 157,
        32 => 165

    ];

    return $posiciones[$lineas] ?? 45;
}

/*
======================================================
REINICIAR LINEAS
======================================================
*/

private function reiniciarLineasProducto($productoId)
{
    Producto::where('id',$productoId)
        ->update(['lineas'=>0]);
}

/*
======================================================
CONTROLADOR IMPRESION INGRESOS
======================================================
*/

public function contraldorImpresionIngresos($id,$indice=0)
{

    $ingreso = Ingreso::findOrFail($id);

    $fecha     = $ingreso->fecha;
    $documento = $ingreso->factura ?? $ingreso->recibo ?? null;

    $detalle = DetalleIngreso::where('ingreso_id',$id)
                ->skip($indice)
                ->first();

    $producto = Producto::findOrFail($detalle->producto_id);

    $cantidad    = $detalle->cantidad;
    $vencimiento = $detalle->vencimiento;
    $saldo       = $producto->saldo;
    $lineas      = (int)$producto->lineas;

    if($lineas == 1){
        return $this->imprimirCabeceraIngreso(
            $producto->id,
            $fecha,
            $documento,
            $cantidad,
            $saldo,
            $vencimiento
        );
    }

    $pdf = new \TCPDF('P','mm',[195,215],true,'UTF-8',false);

    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

    $pdf->SetMargins(0,0,0);
    $pdf->SetAutoPageBreak(false,0);
    $pdf->AddPage();
    $pdf->SetFont('helvetica','',8);

    if($lineas >= 14){

        if($lineas <= 16){
            $posicionY = $this->posicionReversoInicio($lineas);
        }else{
            $posicionY = $this->calcularPosicionReverso($lineas);
        }

    }else{

        $inicio = 58;
        $altoCelda = 8;

        $posicionY = $inicio + (($lineas - 1) * $altoCelda);
    }

    $pdf->SetXY(10,$posicionY);

    $this->lineaIngreso(
        $pdf,
        $fecha,
        $documento,
        $cantidad,
        $saldo,
        $vencimiento
    );

    if($lineas == 32){
        $this->reiniciarLineasProducto($producto->id);
    }

    return $pdf->Output('kardex_ingreso.pdf','I');
}

/*
======================================================
CONTROLADOR IMPRESION EGRESOS
======================================================
*/

public function contraldorImpresionEgresos($id,$indice=0)
{

    $egreso = Egreso::findOrFail($id);

    $fecha     = $egreso->fecha;
    $documento = $egreso->factura ?? $egreso->recibo ?? null;

    $detalle = EgresoDetalle::where('egreso_id',$id)
                ->skip($indice)
                ->first();

    $producto = Producto::findOrFail($detalle->producto_id);

    $cantidad = $detalle->cantidad;
    $saldo    = $producto->saldo;
    $lineas   = (int)$producto->lineas;

    $pdf = new \TCPDF('P','mm',[195,215],true,'UTF-8',false);

    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

    $pdf->SetMargins(0,0,0);
    $pdf->SetAutoPageBreak(false,0);
    $pdf->AddPage();
    $pdf->SetFont('helvetica','',8);

    if($lineas >= 14){

        if($lineas <= 16){
            $posicionY = $this->posicionReversoInicio($lineas);
        }else{
            $posicionY = $this->calcularPosicionReverso($lineas);
        }

    }else{

        $inicio = 58;
        $altoCelda = 8;

        $posicionY = $inicio + (($lineas - 1) * $altoCelda);
    }

    $pdf->SetXY(10,$posicionY);

    $this->lineaEgreso(
        $pdf,
        $fecha,
        $documento,
        $cantidad,
        $saldo
    );

    if($lineas == 32){
        $this->reiniciarLineasProducto($producto->id);
    }

    return $pdf->Output('kardex_egreso.pdf','I');
}

/*
======================================================
FLUJO IMPRESION INGRESO
======================================================
*/

public function flujoImpresionIngreso($id, $indice = 0)
{
    $ingreso = Ingreso::findOrFail($id);

    $detalles = DetalleIngreso::where('ingreso_id', $id)
            ->get()
            ->values();

    if (!isset($detalles[$indice])) {
        return view('impresion.finalizado');
    }

    $detalle = $detalles[$indice];
    $producto = Producto::findOrFail($detalle->producto_id);

    return view('impresion.pantalla', [
        'id'          => $id,
        'indice'      => $indice,
        'total'       => count($detalles),
        'producto'    => $producto->nombre ?? '',
        'urlImprimir' => route('almacen.impresion.ingreso.imprimir', [
                                'id' => $id,
                                'indice' => $indice
                            ])
    ]);
}

/*
======================================================
FLUJO IMPRESION EGRESO
======================================================
*/

public function flujoImpresionEgreso($id, $indice = 0)
{
    $egreso = Egreso::findOrFail($id);

    $detalles = EgresoDetalle::where('egreso_id', $id)
            ->get()
            ->values();

    if (!isset($detalles[$indice])) {
        return view('impresion.finalizado');
    }

    $detalle = $detalles[$indice];
    $producto = Producto::findOrFail($detalle->producto_id);

    return view('impresion.pantallaegreso', [
        'id'          => $id,
        'indice'      => $indice,
        'total'       => count($detalles),
        'producto'    => $producto->nombre ?? '',
        'urlImprimir' => route('almacen.impresion.egreso.imprimir', [
                                'id' => $id,
                                'indice' => $indice
                            ])
    ]);
}

public function imprimirProductoIngreso($id, $indice)
{
    return $this->contraldorImpresionIngresos($id,$indice);
}

public function imprimirProductoEgreso($id, $indice)
{
    return $this->contraldorImpresionEgresos($id,$indice);
}

}
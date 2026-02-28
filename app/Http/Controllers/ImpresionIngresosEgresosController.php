<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Producto;
use setasign\Fpdf\Fpdf;
use App\Models\Ingreso;
use App\Models\DetalleIngreso;

class ImpresionIngresosEgresosController extends Controller
{

   public function imprimirCabeceraIngreso($id = null)
{
    $id = $id ?? 1;

    $producto = $this->obtenerNombre($id);

    if (!$producto) {
        abort(404, 'Producto no encontrado');
    }

    $unidades = Producto::obtenerUnidadNombre($id);

    // ⚠️ Se mantiene tu orientación y tamaño original
    $pdf = new \TCPDF(
        'P',          // NO se cambia
        'mm',
        [195, 215],   // NO se cambia
        true,
        'UTF-8',
        false
    );

    $pdf->SetMargins(0, 0, 0);
    $pdf->SetAutoPageBreak(false, 0);
    $pdf->AddPage();

    // 🔽 Reducimos tamaño de letra en cabecera
    $pdf->SetFont('helvetica', '', 6.5);

    /*
    ======================================================
                        CABECERA
    ======================================================
    */

    // FUNDACION (antes 60.2 mm → ahora 57.2 mm)
    $pdf->SetXY(57.2, 32);
    $pdf->Cell(0, 5, 'FUNDACION CENTRO DE ACOGIDA ARCA DE RESCATE', 0, 1);

    // CENTRAL (2.5 cm = 25 mm)
    $pdf->SetXY(25, 38);
    $pdf->Cell(0, 5, 'CENTRAL', 0, 1);

    // LOCALIZACION (12.5 cm = 125 mm)
    $pdf->SetXY(125, 38);
    $pdf->Cell(0, 5, 'LOCALIZACION: COHACHACA GRANDE-VILOMA', 0, 1);

    // NOMBRE PRODUCTO (2.6 cm = 26 mm)
    $pdf->SetXY(26, 43);
    $pdf->Cell(0, 5, strtoupper($producto['nombre']), 0, 1);

    // UNIDADES (15.6 cm = 156 mm)
    $pdf->SetXY(156, 43);
    $pdf->Cell(0, 5, strtoupper($unidades), 0, 1);

    /*
    ======================================================
            BAJAR 0.5 CM MÁS LA LINEA INGRESO
    ======================================================
    */

    // Antes estaba +10mm → ahora +15mm (1.5 cm total)
    $pdf->SetY($pdf->GetY() + 15);

    /*
    ======================================================
                    LINEA INGRESO
    ======================================================
    */

    $this->lineaIngreso(
    
        date('d-m-Y'),
        '1234',
        15.00,
        15.00,
        null
    );

    return response()->stream(
        fn() => $pdf->Output('Kardex_' . $producto['id'] . '.pdf', 'I'),
        200,
        ['Content-Type' => 'application/pdf']
    );
}

public function imprimirCabeceraEgreso($id = null)
{
    $id = $id ?? 1;

    $producto = $this->obtenerNombre($id);

    if (!$producto) {
        abort(404, 'Producto no encontrado');
    }

    $unidades = Producto::obtenerUnidadNombre($id);

    // Se mantiene orientación y tamaño original
    $pdf = new \TCPDF(
        'P',
        'mm',
        [195, 215],
        true,
        'UTF-8',
        false
    );

    $pdf->SetMargins(0, 0, 0);
    $pdf->SetAutoPageBreak(false, 0);
    $pdf->AddPage();

    // Fuente pequeña para cabecera
    $pdf->SetFont('helvetica', '', 6.5);

    /*
    ======================================================
                        CABECERA
    ======================================================
    */

    // FUNDACION
    $pdf->SetXY(57.2, 32);
    $pdf->Cell(0, 5, 'FUNDACION CENTRO DE ACOGIDA ARCA DE RESCATE', 0, 1);

    // CENTRAL
    $pdf->SetXY(25, 38);
    $pdf->Cell(0, 5, 'CENTRAL', 0, 1);

    // LOCALIZACION
    $pdf->SetXY(125, 38);
    $pdf->Cell(0, 5, 'LOCALIZACION: COHACHACA GRANDE-VILOMA', 0, 1);

    // PRODUCTO
    $pdf->SetXY(26, 43);
    $pdf->Cell(0, 5, strtoupper($producto['nombre']), 0, 1);

    // UNIDADES
    $pdf->SetXY(156, 43);
    $pdf->Cell(0, 5, strtoupper($unidades), 0, 1);

    /*
    ======================================================
        AJUSTE VERTICAL FINAL (ahora +9 mm)
    ======================================================
    */

    $pdf->SetY($pdf->GetY() + 9);

    /*
    ======================================================
                    LINEA EGRESO
    ======================================================
    */

    $this->lineaEgreso($pdf);
      $pdf = new \TCPDF(
        'P',
        'mm',
        [195, 215],
        true,
        'UTF-8',
        false
    );
    return response()->stream(
        fn() => $pdf->Output('Kardex_' . $producto['id'] . '.pdf', 'I'),
        200,
        ['Content-Type' => 'application/pdf']
    );
}
    public function obtenerNombre($id)
    {
        $producto = Producto::findOrFail($id);

        $info = $producto->obtenerNombreProducto(
            $producto->id,
            $producto->categoria_id
        );

        return $info;
    }

    public function imprimeIngreso($id) {}

    public function imprimeEgreso($id) {}



public function lineaIngreso(
    
    $fecha,
    $factura,
    $entrada,
    $saldo,
    $fechaVencimiento = null
) {


    $pdf = new \TCPDF(
        'P',
        'mm',
        [195, 215],
        true,
        'UTF-8',
        false
    );
    $anchos = [21, 17, 14, 5, 12, 17];

    $xBase = 10;
    $yBase = $pdf->GetY() - 5; // dejamos tu ajuste vertical estable

    $x1 = $xBase;
    $x2 = $x1 + $anchos[0];
    $x3 = $x2 + $anchos[1];
    $x4 = $x3 + $anchos[2];

    // 🔥 AQUI ESTA EL AJUSTE GRANDE (1.7 cm)
    $x5 = $x4 + $anchos[3] + 12;  // 17 - 5 = 12 mm

    $x6 = $x5 + $anchos[4] + 3;

    // 1 Fecha
    $pdf->Text($x1, $yBase, $fecha);

    // 2 Factura
    $pdf->Text($x2 + 2, $yBase, $factura);

    // 3 Entrada alineada derecha
    $entradaTexto = number_format($entrada, 2);
    $pdf->Text(
        $x3 + ($anchos[2] - $pdf->GetStringWidth($entradaTexto) - 1),
        $yBase,
        $entradaTexto
    );

    // 4 Columna vacía (no imprimir nada)

    // 5 Saldo alineado derecha correctamente dentro de su nuevo espacio
    $saldoTexto = number_format($saldo, 2);
    $pdf->Text(
        $x5 + ($anchos[4] - $pdf->GetStringWidth($saldoTexto) - 2),
        $yBase,
        $saldoTexto
    );

    // 6 Fecha vencimiento
    $pdf->Text($x6, $yBase, $fechaVencimiento ?? '');
}
public function lineaEgreso($pdf)
{
     $pdf = new \TCPDF(
        'P',
        'mm',
        [195, 215],
        true,
        'UTF-8',
        false
    );
    $fecha = now()->format('d-m-Y');
    $numeroRecibo = rand(1000, 9999);
    $valor1 = rand(100, 5000) / 10;
    $valor2 = rand(100, 5000) / 10;

    $alto = 8;

    $anchos = [
        21, 17, 16, 14, 15, 16, 18, 19, 17, 19, 22
    ];

    $pdf->SetX(10);

    $pdf->Cell($anchos[0], $alto, $fecha, 0, 0, 'C');
    $pdf->Cell($anchos[1], $alto, $numeroRecibo, 0, 0, 'C');
    $pdf->Cell($anchos[2], $alto, '', 0, 0, 'C');
    $pdf->Cell($anchos[3], $alto, number_format($valor1, 2), 0, 0, 'C');
    $pdf->Cell($anchos[4], $alto, number_format($valor2, 2), 0, 0, 'C');
    $pdf->Cell($anchos[5], $alto, '', 0, 0, 'C');
    $pdf->Cell($anchos[6], $alto, '', 0, 0, 'C');
    $pdf->Cell($anchos[7], $alto, '', 0, 0, 'C');
    $pdf->Cell($anchos[8], $alto, '', 0, 0, 'C');
    $pdf->Cell($anchos[9], $alto, '', 0, 0, 'C');
    $pdf->Cell($anchos[10], $alto, '', 0, 1, 'C');
}
public function imprimirLineaEgreso()
{
    $pdf = new \TCPDF(
        'P',              // 👈 IMPORTANTE (no usar L)
        'mm',
        [215, 165],       // Tu tamaño real
        true,
        'UTF-8',
        false
    );

    $pdf->SetMargins(5, 5, 5); // pequeño margen para que no corte
    $pdf->SetAutoPageBreak(false, 0);
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 8);

    $fila = rand(2, 13);

    $inicio = 64;
    $altoCelda = 8;

    $posicionY = $inicio + (($fila - 2) * $altoCelda);

    $pdf->SetY($posicionY);

    $this->lineaEgreso($pdf);

    return $pdf->Output('posicion_fila.pdf', 'I');
}

public function imprimirLineaIngreso()
{
    $pdf = new \TCPDF(
        'P',
        'mm',
        [215, 165],
        true,
        'UTF-8',
        false
    );

    $pdf->SetMargins(5, 5, 5);
    $pdf->SetAutoPageBreak(false, 0);
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 8);

    $fila = rand(2, 13);

    // 🔼 Subimos 3 mm
    $inicio = 63;   // antes 66
    $altoCelda = 8;

    $posicionY = $inicio + (($fila - 2) * $altoCelda);

    $pdf->SetY($posicionY);

    $fecha = now()->format('d-m-Y');
    $factura = rand(1000, 9999);
    $entrada = rand(100, 5000) / 10;
    $saldo = rand(100, 5000) / 10;
    $fechaVencimiento = now()->addDays(30)->format('d-m-Y');

    $this->lineaIngreso(
        
        $fecha,
        $factura,
        $entrada,
        $saldo,
        $fechaVencimiento
    );

    return $pdf->Output('linea_ingreso.pdf', 'I');
}

public function imprimirReversoIngreso( $fecha,
                $documento,
                $cantidad,
                $saldo,
                $vencimiento)
{
    $pdf = new \TCPDF(
        'P',
        'mm',
        [195, 215],
        true,
        'UTF-8',
        false
    );

    $pdf->SetMargins(0, 0, 0);
    $pdf->SetAutoPageBreak(false, 0);
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 7);

    // 2.5 cm desde el borde superior
    $pdf->SetY(25);

    $this->lineaIngreso(
                $fecha,
                $documento,
                $cantidad,
                $saldo,
                $vencimiento,
    );

    return response()->stream(
        fn() => $pdf->Output('Reverso_Ingreso.pdf', 'I'),
        200,
        ['Content-Type' => 'application/pdf']
    );
}

public function imprimirReversoEgreso($id = null)
{
    $pdf = new \TCPDF(
        'P',
        'mm',
        [195, 215],
        true,
        'UTF-8',
        false
    );

    $pdf->SetMargins(0, 0, 0);
    $pdf->SetAutoPageBreak(false, 0);
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 7);

    // Posición final ajustada: 18 mm desde el borde superior
    $pdf->SetY(18);

    $this->lineaEgreso($pdf);

    return response()->stream(
        fn() => $pdf->Output('Reverso_Egreso.pdf', 'I'),
        200,
        ['Content-Type' => 'application/pdf']
    );
}

public function contraldorImpresionIngresos($id)
{
    // 1️⃣ Obtener ingreso
    $ingreso = Ingreso::findOrFail($id);

    $fecha     = $ingreso->fecha;
    $documento = $ingreso->factura ?? $ingreso->recibo ?? null;

    // 2️⃣ Obtener detalles del ingreso
    $detalles = DetalleIngreso::where('ingreso_id', $id)->get();

    if ($detalles->isEmpty()) {
        abort(404, 'El ingreso no tiene productos.');
    }

    // 3️⃣ Recorrer productos del ingreso
    foreach ($detalles as $detalle) {

        $producto = Producto::findOrFail($detalle->producto_id);

        $cantidad    = $detalle->cantidad;
        $vencimiento = $detalle->vencimiento;
        $saldo       = $producto->saldo;
        $lineas      = $producto->lineas;

        // 4️⃣ Según cantidad de líneas llamar función correcta

        if ($lineas == 1) {

            return $this->imprimirCabeceraIngreso($producto->id);

        } elseif ($lineas >= 2 && $lineas <= 13) {

            return $this->imprimirLineaIngreso();

        } elseif ($lineas == 14) {

            return $this->imprimirReversoIngreso(
                $fecha,
                $documento,
                $cantidad,
                $saldo,
                $vencimiento
            );

        } elseif ($lineas >= 15 && $lineas <= 32) {

            return $this->imprimirLineaIngreso();
        }
    }

    abort(404, 'No se pudo determinar el tipo de impresión.');
}
}

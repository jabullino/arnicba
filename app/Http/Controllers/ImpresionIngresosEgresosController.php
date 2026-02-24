<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Producto;
use strtoupper;

class ImpresionIngresosEgresosController extends Controller
{

    public function imprimirCabecera($id = null)
{
    $id = $id ?? 24;

    $producto = $this->obtenerNombre($id);
    $unidades = Producto::obtenerUnidadNombre($id);

    // Aumentamos más el ancho pero sigue siendo vertical
    $width  = 19.5 * 28.3465;   // antes 18
    $height = 21.5 * 28.3465;   // alto mayor = vertical

    $html = '
    <html>
    <head>
        <meta charset="utf-8">
        <style>
            @page { margin:0; }

            html, body {
                margin:0;
                padding:0;
                font-family: DejaVu Sans, sans-serif;
                font-size:10px;
                position:relative;
            }

            .campo { position:absolute; }
        </style>
    </head>
    <body>

        <div class="campo" style="left:6.0cm; top:3.2cm;">
            CENTRO DE ACOGIDA ARCA DE RESCATE
        </div>

        <div class="campo" style="left:2.6cm; top:3.8cm;">
            CENTRAL
        </div>

        <div class="campo" style="left:12.3cm; top:3.8cm;">
            LOCALIZACION: COHACHACA GRANDE-VILOMA
        </div>

        <div class="campo" style="left:2.8cm; top:4.3cm;">
            ' . strtoupper($producto['nombre']) . '
        </div>

        <div class="campo" style="left:15.5cm; top:4.3cm;">
            ' . strtoupper($unidades) . '
        </div>

    </body>
    </html>
    ';

    return Pdf::loadHTML($html)
        ->setPaper([0, 0, $width, $height]) // sin landscape
        ->stream('Kardex_' . $producto['id'] . '.pdf');
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

    public function imprimeIngreso($id){


    }

    public function imprimeEgreso($id){


    }
}

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">

<style>
body { font-family: Arial; margin:10px; }

table, th, td {
    border:1px solid black;
    border-collapse:collapse;
    padding:6px;
}

.text-center { text-align:center; }
.text-right { text-align:right; }

/* COLORES */
.bg-azul { background:#1e3a8a; color:white; }
.bg-gris { background:#374151; color:white; }
.bg-verde { background:#065f46; color:white; }
.bg-rojo { background:#b91c1c; color:white; }
.bg-amarillo { background:#fef08a; }

@media print {
    * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
}
</style>
</head>

<body>

{{-- ===================== TABLA SUPERIOR ===================== --}}
<table width="100%">

<thead>
<tr>
<th colspan="7" class="bg-azul text-center">
REPORTE GENERAL DE GASTOS POR CUENTA, SUBCUENTA PERIODO
{{ \Carbon\Carbon::parse($fecinicio)->format('d-m-Y') }}
{{ \Carbon\Carbon::parse($fecfin)->format('d-m-Y') }}
</th>
</tr>

<tr class="bg-azul text-center">
<th>Item</th>
<th>Num. Asiento</th>
<th>Fecha</th>
<th>Factura</th>
<th>Recibo</th>
<th>Importe Bs.</th>
<th>Importe $us</th>
</tr>
</thead>

<tbody>

@foreach ($asientos as $asi)

@if ($loop->first)
@php
$nombrecuenta = $cuentaux->getCuenta($asi->cuenta);
$nombresubcuenta = $subcuentaux->getSubcuenta($asi->sub_cuenta);
$cuentaidactual = $asi->cuenta;
$subcuentaidactual = $asi->sub_cuenta;
@endphp

<tr class="bg-gris"><td colspan="7" class="text-center"><strong>{{ $nombrecuenta }}</strong></td></tr>
<tr class="bg-verde"><td colspan="7" class="text-center"><strong>{{ $nombresubcuenta }}</strong></td></tr>
@endif

@if ($cuentaidactual == $asi->cuenta && $subcuentaidactual != $asi->sub_cuenta)
<tr class="bg-rojo">
<td colspan="5" class="text-right"><strong>TOTAL SUBCUENTA</strong></td>
<td>{{ number_format($sumasubcuentabs,2) }}</td>
<td>{{ number_format($sumasubcuentasus,2) }}</td>
</tr>

@php
$sumasubcuentabs = 0;
$sumasubcuentasus = 0;
$nombresubcuenta = $subcuentaux->getSubcuenta($asi->sub_cuenta);
$subcuentaidactual = $asi->sub_cuenta;
@endphp

<tr class="bg-verde"><td colspan="7" class="text-center"><strong>{{ $nombresubcuenta }}</strong></td></tr>
@endif

@if ($cuentaidactual != $asi->cuenta)
<tr class="bg-rojo">
<td colspan="5" class="text-right"><strong>TOTAL SUBCUENTA</strong></td>
<td>{{ number_format($sumasubcuentabs,2) }}</td>
<td>{{ number_format($sumasubcuentasus,2) }}</td>
</tr>

<tr class="bg-azul">
<td colspan="4"></td>
<td class="text-right"><strong>TOTAL</strong></td>
<td>{{ number_format($montobs,2) }}</td>
<td>{{ number_format($montosus,2) }}</td>
</tr>

@php
$montobs = 0;
$montosus = 0;
$sumasubcuentabs = 0;
$sumasubcuentasus = 0;

$nombrecuenta = $cuentaux->getCuenta($asi->cuenta);
$nombresubcuenta = $subcuentaux->getSubcuenta($asi->sub_cuenta);
$cuentaidactual = $asi->cuenta;
$subcuentaidactual = $asi->sub_cuenta;
@endphp

<tr class="bg-gris"><td colspan="7" class="text-center"><strong>{{ $nombrecuenta }}</strong></td></tr>
<tr class="bg-verde"><td colspan="7" class="text-center"><strong>{{ $nombresubcuenta }}</strong></td></tr>
@endif

<tr>
<td class="text-center">{{ $cont = $cont + 1 }}</td>
<td class="text-center">{{ $asi->id }}</td>
<td class="text-center">{{ \Carbon\Carbon::parse($asi->fec_asiento)->format('d-m-Y') }}</td>
<td class="text-center">{{ $asi->factura }}</td>
<td class="text-center">{{ $asi->recibo }}</td>
<td class="text-center">{{ number_format($asi->monto_bs,2) }}</td>
<td class="text-center">{{ number_format($asi->monto_sus,2) }}</td>
</tr>

@php
$sumatotalbs += $asi->monto_bs;
$sumatotalsus += $asi->monto_sus;
$montobs += $asi->monto_bs;
$montosus += $asi->monto_sus;
$sumasubcuentabs += $asi->monto_bs;
$sumasubcuentasus += $asi->monto_sus;
@endphp

@if ($loop->last)
<tr class="bg-rojo">
<td colspan="5" class="text-right"><strong>TOTAL SUBCUENTA</strong></td>
<td>{{ number_format($sumasubcuentabs,2) }}</td>
<td>{{ number_format($sumasubcuentasus,2) }}</td>
</tr>

<tr class="bg-azul">
<td colspan="4"></td>
<td class="text-right"><strong>TOTAL</strong></td>
<td>{{ number_format($montobs,2) }}</td>
<td>{{ number_format($montosus,2) }}</td>
</tr>
@endif

@endforeach

<tr class="bg-azul">
<td colspan="5" class="text-right"><strong>TOTAL GENERAL</strong></td>
<td>{{ number_format($sumatotalbs,2) }}</td>
<td>{{ number_format($sumatotalsus,2) }}</td>
</tr>

</tbody>
</table>

<br><br>

{{-- ===================== TABLA INFERIOR COMPLETA ===================== --}}
<div class="bg-amarillo" style="padding:10px;">

<table width="100%">

<tr><td colspan="3" style="border:none" class="text-center"><strong>HOJA DE PRESENTACIÓN DE ESTADO DE RESULTADOS</strong></td></tr>
<tr><td colspan="3" style="border:none" class="text-center"><strong>FUNDACIÓN ARCA DE RESCATE DE LOS NIÑOS</strong></td></tr>
<tr><td colspan="3" style="border:none" class="text-center"><strong>{{ $nombremes }} {{ $anio }}</strong></td></tr>

<tr>
<td colspan="3" style="border:none" class="text-center">
Del {{ \Carbon\Carbon::parse($fecinicio)->format('d-m-Y') }}
al {{ \Carbon\Carbon::parse($fecfin)->format('d-m-Y') }}
</td>
</tr>

<tr><td colspan="3" style="border:none" class="text-center"><strong>Expresado en Dólares Estadounidenses</strong></td></tr>

{{-- INGRESOS --}}

<tr class="bg-rojo">
<td><strong>TOTAL INGRESOS</strong></td>
<td></td>
<td class="text-right">{{ number_format($sumacreditos + $saldo + $interescredito,2) }}</td>
</tr>

<tr class="bg-gris"><td colspan="3"><strong>INGRESOS</strong></td></tr>

<tr>
<td>Ingresos del mes</td>
<td class="text-right">{{ number_format($sumacreditos,2) }}</td>
<td style='border:none'></td>
</tr>

<tr>
<td>Saldo inicial</td>
<td class="text-right">{{ number_format($saldo,2) }}</td>
<td style='border:none'></td>
</tr>

@foreach ($detallecreditos as $detalle)
<tr>
<td>Depósito {{ \Carbon\Carbon::parse($detalle->fecha)->format('d-m-Y') }}</td>
<td class="text-right">{{ number_format($detalle->credito,2) }}</td>
<td style='border:none'></td>
</tr>
@endforeach

<tr>
    <td>OTROS INGRESOS</td>
    <td class="text-right">{{ number_format($interescredito,2)}}</td>
    <td style='border:none'></td>
</tr>

<tr>
    <td>Interés ganado</td>
    <td class="text-right">{{ number_format($interescredito,2) }}</td>
    <td style='border:none'></td>
</tr>

{{-- EGRESOS --}}

<tr class="bg-rojo">
<td><strong>TOTAL EGRESOS</strong></td>
<td></td>
<td class="text-right">{{ number_format($gastosoperativos,2) }}</td>
</tr>

<tr class="bg-gris"><td colspan="3"><strong>EGRESOS</strong></td></tr>

@foreach ($resultados as $fila)
<tr>
<td>{{ $fila->cuenta_nombre }}</td>
<td class="text-right">{{ number_format($fila->total,2) }}</td>
<td style='border:none'></td>
</tr>
@endforeach


<tr class="bg-gris">
<td><strong>SALDO FINAL</strong></td>
<td></td>
<td class="text-right">{{ number_format(($sumacreditos + $saldo + $interescredito - $gastosoperativos),2) }}</td>
</tr>

<tr class="bg-verde">
<td><strong>VARIACIÓN</strong></td>
<td></td>
<td class="text-right">{{ number_format(($sumacreditos + $saldo + $interescredito - $gastosoperativos - $saldofinal),2) }}</td>
</tr>

<tr class="bg-rojo">
<td><strong>SALDO BANCARIO</strong></td>
<td></td>
<td class="text-right">{{ number_format($saldofinal,2) }}</td>
</tr>

{{-- ESPACIO FIRMA --}}
<tr><td colspan="3" style="height:140px; border:none;"></td></tr>

<tr>
<td class="text-center" style="border:none;">
<div style="border-top:1px solid black; width:80%; margin:auto;"></div>
<strong>Lic. Aud. Silvia Aguilar García<br>CADB 19836<br>CDA-16-MM85</strong>
</td>

<td style="border:none;"></td>

<td class="text-center" style="border:none;">
<div style="border-top:1px solid black; width:80%; margin:auto;"></div>
<strong>Ing. Javier Fidel Guillén Escalera<br>C.I. 997213 c.b.a<br>Representante Legal</strong>
</td>
</tr>

</table>
</div>

</body>
</html>

@extends('layouts.app')
@section('content')

<style>
    table, th, td {
        border: 1px solid #000;
        border-collapse: collapse;
        padding: 8px;
    }

    .tabla-reporte {
        background-color: #0f172a;
        color: #e5e7eb;
    }

    .encabezado {
        background-color: #000000 !important;
        color: #ffffff !important;
        font-weight: bold;
    }

    .fila-cuenta {
        background-color: #0033cc !important;
        color: #ffffff !important;
        font-weight: bold;
    }

    .fila-subcuenta {
        background-color: #333333 !important;
        color: #ffffff !important;
        font-weight: bold;
    }

    @media print {
        body, table {
            background: #ffffff !important;
            color: #000 !important;
        }

        .encabezado {
            background: #fff !important;
            color: #000 !important;
            border: 2px solid #000 !important;
        }

        .fila-cuenta {
            background: #e0e0e0 !important;
            color: #000 !important;
        }

        .fila-subcuenta {
            background: #f5f5f5 !important;
            color: #000 !important;
        }

        td, th {
            border: 1px solid #000 !important;
            color: #000 !important;
        }
    }
</style>

<div class='card'>
<div class="card-body">

<table class="table tabla-reporte">

<thead>
<tr>
<td colspan="7" class="encabezado" style="text-align:center !important;">
REPORTE GENERAL DE GASTOS POR CUENTA, SUBCUENTA PERIODO
<br>
{{ \Carbon\Carbon::parse($fecinicio)->format('d-m-Y') }}
-
{{ \Carbon\Carbon::parse($fecfin)->format('d-m-Y') }}
</td>
</tr>

<tr class="encabezado">
<th style="text-align:center !important;">#</th>
<th style="text-align:center !important;">Asiento</th>
<th style="text-align:center !important;">Fecha</th>
<th style="text-align:center !important;">Factura</th>
<th style="text-align:center !important;">Recibo</th>
<th style="text-align:center !important;">Bs</th>
<th style="text-align:center !important;">$us</th>
</tr>
</thead>

<tbody>

@php
$cont = 0;
$cuentaActual = null;
$subcuentaActual = null;

$subtotalBs = 0;
$subtotalSus = 0;

$totalCuentaBs = 0;
$totalCuentaSus = 0;

$totalGeneralBs = 0;
$totalGeneralSus = 0;
@endphp

@foreach ($asientos as $asi)

@if ($subcuentaActual !== null && $subcuentaActual != $asi->sub_cuenta)
<tr>
<td colspan="5" style="background:#ff9800 !important; text-align:center !important;"><b>SUBTOTAL</b></td>
<td style="background:#ff9800 !important; text-align:center !important;"><b>{{ number_format($subtotalBs,2) }}</b></td>
<td style="background:#ff9800 !important; text-align:center !important;"><b>{{ number_format($subtotalSus,2) }}</b></td>
</tr>

@php
$subtotalBs = 0;
$subtotalSus = 0;
@endphp
@endif

@if ($cuentaActual !== $asi->cuenta)

@if ($cuentaActual !== null)
<tr>
<td colspan="5" style="background:#00c853 !important; text-align:center !important;"><b>TOTAL CUENTA</b></td>
<td style="background:#00c853 !important; text-align:center !important;"><b>{{ number_format($totalCuentaBs,2) }}</b></td>
<td style="background:#00c853 !important; text-align:center !important;"><b>{{ number_format($totalCuentaSus,2) }}</b></td>
</tr>
@endif

<tr class="fila-cuenta">
<td colspan="7" style="text-align:center !important;">
{{ $cuentaux->getCuenta($asi->cuenta) }}
</td>
</tr>

@php
$cuentaActual = $asi->cuenta;
$subcuentaActual = null;
$subtotalBs = 0;
$subtotalSus = 0;
$totalCuentaBs = 0;
$totalCuentaSus = 0;
@endphp
@endif

@if ($subcuentaActual !== $asi->sub_cuenta)
<tr class="fila-subcuenta">
<td colspan="7" style="text-align:center !important;">
{{ $subcuentaux->getSubcuenta($asi->sub_cuenta) }}
</td>
</tr>

@php
$subcuentaActual = $asi->sub_cuenta;
@endphp
@endif

<tr>
<td style="text-align:center !important;">{{ ++$cont }}</td>
<td style="text-align:center !important;">{{ $asi->id }}</td>
<td style="text-align:center !important;">{{ \Carbon\Carbon::parse($asi->fec_asiento)->format('d-m-Y') }}</td>
<td style="text-align:center !important;">{{ $asi->factura }}</td>
<td style="text-align:center !important;">{{ $asi->recibo }}</td>
<td style="text-align:center !important;">{{ $asi->monto_bs }}</td>
<td style="text-align:center !important;">{{ $asi->monto_sus }}</td>
</tr>

@php
$subtotalBs += $asi->monto_bs;
$subtotalSus += $asi->monto_sus;
$totalCuentaBs += $asi->monto_bs;
$totalCuentaSus += $asi->monto_sus;
$totalGeneralBs += $asi->monto_bs;
$totalGeneralSus += $asi->monto_sus;
@endphp

@endforeach

<tr>
<td colspan="5" style="background:#ff9800 !important; text-align:center !important;"><b>SUBTOTAL</b></td>
<td style="background:#ff9800 !important; text-align:center !important;"><b>{{ number_format($subtotalBs,2) }}</b></td>
<td style="background:#ff9800 !important; text-align:center !important;"><b>{{ number_format($subtotalSus,2) }}</b></td>
</tr>

<tr>
<td colspan="5" style="background:#00c853 !important; text-align:center !important;"><b>TOTAL CUENTA</b></td>
<td style="background:#00c853 !important; text-align:center !important;"><b>{{ number_format($totalCuentaBs,2) }}</b></td>
<td style="background:#00c853 !important; text-align:center !important;"><b>{{ number_format($totalCuentaSus,2) }}</b></td>
</tr>

<tr>
<td colspan="5" style="background:#ff1744 !important; color:#fff; text-align:center !important;"><b>TOTAL GENERAL</b></td>
<td style="background:#ff1744 !important; color:#fff; text-align:center !important;"><b>{{ number_format($totalGeneralBs,2) }}</b></td>
<td style="background:#ff1744 !important; color:#fff; text-align:center !important;"><b>{{ number_format($totalGeneralSus,2) }}</b></td>
</tr>

</tbody>
</table>

</div>
</div>

@endsection
@extends('layouts.app')
@section('content')

<style>
table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
    padding: 8px;
}

/* 🔥 COLORES FUERTES PARA DARK MODE */
.titulo-cuenta {
    background-color: #1f2937 !important;
    color: #ffffff !important;
    font-weight: bold;
}

.titulo-subcuenta {
    background-color: #0f766e !important;
    color: #ffffff !important;
    font-weight: bold;
}

.total-subcuenta {
    background-color: #dc2626 !important;
    color: #ffffff !important;
    font-weight: bold;
}

.total-general {
    background-color: #1e40af !important;
    color: #ffffff !important;
    font-weight: bold;
}

.total-final {
    background-color: #15803d !important;
    color: #ffffff !important;
    font-weight: bold;
}
</style>

<div class='card'>
<div class='card-body'>

<table class="table table-striped">

<thead>
<tr>
<th>Item #</th>
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

<tr>
<td colspan="7" class="titulo-cuenta text-center">
{{ $nombrecuenta }}
</td>
</tr>

<tr>
<td colspan="7" class="titulo-subcuenta text-center">
{{ $nombresubcuenta }}
</td>
</tr>

@endif

{{-- CAMBIO DE SUBCUENTA --}}
@if ($subcuentaidactual != $asi->sub_cuenta)

<tr>
<td colspan="5" class="total-subcuenta text-right">
TOTAL SUBCUENTA
</td>
<td class="total-subcuenta text-center">
{{ number_format($sumasubcuentabs,2) }}
</td>
<td class="total-subcuenta text-center">
{{ number_format($sumasubcuentasus,2) }}
</td>
</tr>

@php
$sumasubcuentabs = 0;
$sumasubcuentasus = 0;
$nombresubcuenta = $subcuentaux->getSubcuenta($asi->sub_cuenta);
@endphp

<tr>
<td colspan="7" class="titulo-subcuenta text-center">
{{ $nombresubcuenta }}
</td>
</tr>

@endif

{{-- FILA NORMAL --}}
<tr>
<td>{{ $cont = $cont + 1 }}</td>
<td>{{ $asi->id }}</td>
<td>{{ \Carbon\Carbon::parse($asi->fec_asiento)->format('d-m-Y') }}</td>
<td>{{ $asi->factura }}</td>
<td>{{ $asi->recibo }}</td>
<td>{{ $asi->monto_bs }}</td>
<td>{{ $asi->monto_sus }}</td>
</tr>

@php
$sumatotalbs += $asi->monto_bs;
$sumatotalsus += $asi->monto_sus;
$montobs += $asi->monto_bs;
$montosus += $asi->monto_sus;
$sumasubcuentabs += $asi->monto_bs;
$sumasubcuentasus += $asi->monto_sus;

// 🔥 FIX CLAVE
$subcuentaidactual = $asi->sub_cuenta;
@endphp

{{-- ÚLTIMO --}}
@if ($loop->last)

<tr>
<td colspan="5" class="total-subcuenta text-right">
TOTAL SUBCUENTA
</td>
<td class="total-subcuenta text-center">
{{ number_format($sumasubcuentabs,2) }}
</td>
<td class="total-subcuenta text-center">
{{ number_format($sumasubcuentasus,2) }}
</td>
</tr>

<tr>
<td colspan="5" class="total-general text-right">
TOTAL
</td>
<td class="total-general text-center">
{{ number_format($montobs,2) }}
</td>
<td class="total-general text-center">
{{ number_format($montosus,2) }}
</td>
</tr>

@endif

@endforeach

<tr>
<td colspan="5" class="total-final text-right">
TOTAL GASTOS EN EL PERÍODO
</td>
<td class="total-final text-center">
{{ number_format($sumatotalbs,2) }}
</td>
<td class="total-final text-center">
{{ number_format($sumatotalsus,2) }}
</td>
</tr>

</tbody>
</table>

</div>
</div>

@endsection

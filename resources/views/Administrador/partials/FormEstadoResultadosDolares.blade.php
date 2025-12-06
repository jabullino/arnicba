<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Estado de Resultados</title>
    <style>
        table,
        th,
        td {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 8px;
        }

        @media print {

            /* Sidebar */
            .main-sidebar,
            .sidebar,
            aside.sidebar,
            /* Navbar superior */
            .main-header,
            .navbar,
            /* Footer */
            .main-footer,
            /* Control sidebar (barra lateral derecha) */
            .control-sidebar,
            /* Botones u otros elementos que no quieres */
            .btn,
            #btnImprimir,
            #btnReporte {
                display: none !important;
            }

            /* Contenido principal para ocupar todo el ancho */
            .content-wrapper,
            .content {
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
            }
        }

        .contenedor {
            margin-top: 400px;
            width: 1200px;
            /* Aseguramos que haya suficiente espacio */
            margin-left: auto;
            margin-right: auto;
        }

        .caja {
            display: inline-block;
            width: 350px;
            vertical-align: top;
        }

        .espaciador {
            display: inline-block;
            width: 300px;
        }

        .contenido {
            text-align: center;
            background: #f0f0f0;
            padding: 10px;
        }
    </style>
</head>

<body>
    <div><!---div principal--->
        <div class='card'>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card-body">

                <table class="table table-striped mb-2 pagination  mx-auto mt-0">

                    <tbody>
                        <thead>

                            <tr colspan='7'>
                                <div class='card-header'
                                    style='font-weight:bold;text-align:center;text-decoration:underline'>
                                    REPORTE GENERAL DE GASTOS POR CUENTA, SUBCUENTA PERIODO
                                    {{ \Carbon\Carbon::parse($fecinicio)->format('d-m-Y') }} &nbsp;
                                    {{ \Carbon\Carbon::parse($fecfin)->format('d-m-Y') }}

                                </div>
                            </tr>
                            <tr class='bg-sky-900 text-black bold'>
                                <th colspan="1" style='width:32px;text-align:center;background-color:crimson'>Item #
                                </th>
                                <th style='width:32px;text-align:center;background-color:crimson'>Num. Asiento</th>
                                <th style='width:96px;text-align:center;background-color:crimson'>Fecha</th>
                                <th style='width:96px;text-align:center;background-color:crimson'>Factura</th>
                                <th style='width:96px;text-align:center;background-color:crimson'>Recibo</th>
                                <th style='width:192px;text-align:center;background-color:crimson'>Importe Bs.</th>
                                <th style='width:192px;text-align:center;background-color:crimson'>Importe $us</th>

                            </tr>
                        </thead>
                        @foreach ($asientos as $asi)
                            @if ($cont == 0)
                                @php
                                    $nombrecuenta = $cuentaux->getCuenta($asi->cuenta);
                                    $nombresubcuenta = $subcuentaux->getSubcuenta($asi->sub_cuenta);
                                    $nombrecuentas[$contadorcuentas] = $nombrecuenta;
                                    $contadorcuentas = $contadorcuentas + 1;
                                @endphp
                                <tr>
                                    <td colspan="7"
                                        style='font-weight:bold;text-align:center;background-color:#a5640ee0'>
                                        {{ $nombrecuenta }}</td>
                                </tr>
                                <tr>
                                    <td colspan="7" style='text-align:center;background-color:#e6f092'>
                                        {{ $nombresubcuenta }}</td>
                                </tr>
                                <tr>

                                    <th scope="row" class='text-center'>{{ $cont = $cont + 1 }}</th>
                                    <th scope="row" class='text-center'>{{ $asi->id }}</th>
                                    <td class='text-center'>
                                        {{ \Carbon\Carbon::parse($asi->fec_asiento)->format('d-m-Y') }}
                                    </td>
                                    <td class='text-center'>{{ $asi->factura }}</td>
                                    <td class='text-center'>{{ $asi->recibo }}</td>
                                    <td class='text-center'>{{ $asi->monto_bs }}</td>
                                    <td class='text-center'>{{ $asi->monto_sus }}</td>
                                </tr>
                                @php
                                    $sumatotalbs += $asi->monto_bs;
                                    $sumatotalsus += $asi->monto_sus;
                                    $montobs += $asi->monto_bs;
                                    $montosus += $asi->monto_sus;
                                    $sumasubcuentabs += $asi->monto_bs;
                                    $sumasubcuentasus += $asi->monto_sus;
                                    $cuentaidactual = $asi->cuenta;
                                    $subcuentaidactual = $asi->sub_cuenta;

                                @endphp
                                @if ($loop->last)
                                    <tr>
                                        <td colspan="5"
                                            style='font-weight:bold;text-align:right;background-color:#d6aa31'
                                            class='bg-red-700 text-black text-right text-lg bold'>TOTAL
                                            SUBCUENTA</td>
                                        <td colspan="1"
                                            style='font-weight:bold;text-align:right;background-color:#d6aa31'
                                            class=' bg-red-700 text-black text-lg bold text-center'>
                                            {{ number_format($sumasubcuentabs, 2) }}</td>

                                        <td colspan="1"
                                            style='font-weight:bold;text-align:right;background-color:#d6aa31'
                                            class='bg-red-700 text-black text-lg bold text-center'>
                                            {{ number_format($sumasubcuentasus, 2) }}
                                        </td>

                                    </tr>
                                    <tr>
                                        <td colspan="4"
                                            style='font-weight:bold;text-align:right;background-color:#348a7b'
                                            class='bg-sky-900'></td>
                                        <td colspan="1"
                                            style='font-weight:bold;text-align:right;background-color:#348a7b'
                                            class='bg-sky-900 text-black text-right text-lg bold'>TOTAL
                                        </td>
                                        <td colspan="1"style='font-weight:bold;text-align:right;background-color:#348a7b'
                                            class='bg-sky-900 text-black text-lg bold text-center'>
                                            {{ number_format($montobs, 2) }}</td>
                                        <td colspan="1"
                                            style='font-weight:bold;text-align:right;background-color:#348a7b'
                                            class='bg-sky-900  text-black text-lg bold text-center'>
                                            {{ number_format($montosus, 2) }}</td>
                                    </tr>
                                @endif
                            @elseif($cuentaidactual == $asi->cuenta && $subcuentaidactual == $asi->sub_cuenta)
                                <tr>

                                    <th scope="row" class='text-center'>{{ $cont = $cont + 1 }}</th>
                                    <th scope="row" class='text-center'>{{ $asi->id }}</th>
                                    <td class='text-center'>
                                        {{ \Carbon\Carbon::parse($asi->fec_asiento)->format('d-m-Y') }}
                                    </td>
                                    <td class='text-center'>{{ $asi->factura }}</td>
                                    <td class='text-center'>{{ $asi->recibo }}</td>
                                    <td class='text-center'>{{ $asi->monto_bs }}</td>
                                    <td class='text-center'>{{ $asi->monto_sus }}</td>
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
                                    <tr>
                                        <td colspan="5"
                                            style='font-weight:bold;text-align:right;background-color:#d6aa31'
                                            class='bg-red-700 text-black text-right text-lg bold'>TOTAL
                                            SUBCUENTA</td>
                                        <td colspan="1"
                                            style='font-weight:bold;text-align:right;background-color:#d6aa31'
                                            class=' bg-red-700 text-black text-lg bold text-center'>
                                            {{ number_format($sumasubcuentabs, 2) }}</td>

                                        <td colspan="1"
                                            style='font-weight:bold;text-align:right;background-color:#d6aa31'
                                            class='bg-red-700 text-black text-lg bold text-center'>
                                            {{ number_format($sumasubcuentasus, 2) }}
                                        </td>

                                    </tr>
                                    <tr>
                                        <td colspan="4"
                                            style='font-weight:bold;text-align:right;background-color:#348a7b'
                                            class='bg-sky-900'></td>
                                        <td colspan="1"
                                            style='font-weight:bold;text-align:right;background-color:#348a7b'
                                            class='bg-sky-900 text-black text-right text-lg bold'>TOTAL
                                        </td>
                                        <td colspan="1"
                                            style='font-weight:bold;text-align:right;background-color:#348a7b'
                                            class='bg-sky-900 text-black text-lg bold text-center'>
                                            {{ number_format($montobs, 2) }}</td>
                                        <td colspan="1"
                                            style='font-weight:bold;text-align:right;background-color:#348a7b'
                                            class='bg-sky-900  text-black text-lg bold text-center'>
                                            {{ number_format($montosus, 2) }}</td>
                                    </tr>
                                @endif
                            @elseif($cuentaidactual == $asi->cuenta && $subcuentaidactual !== $asi->sub_cuenta)
                                <tr>
                                    <td colspan="5"
                                        style='font-weight:bold;text-align:right;background-color:#d6aa31'
                                        class='bg-red-700 text-black text-right text-lg bold'>TOTAL
                                        SUBCUENTA</td>
                                    <td colspan="1"
                                        style='font-weight:bold;text-align:right;background-color:#d6aa31'
                                        class=' bg-red-700 text-black text-lg bold text-center'>
                                        {{ number_format($sumasubcuentabs, 2) }}</td>

                                    <td colspan="1"
                                        style='font-weight:bold;text-align:right;background-color:#d6aa31'
                                        class='bg-red-700 text-black text-lg bold text-center'>
                                        {{ number_format($sumasubcuentasus, 2) }}
                                    </td>
                                </tr>
                                @php

                                    $sumasubcuentabs = 0;
                                    $sumasubcuentasus = 0;

                                @endphp
                                @php

                                    $nombresubcuenta = $subcuentaux->getSubcuenta($asi->sub_cuenta);
                                @endphp
                                <tr>
                                    <td colspan="7" style='text-align:center;background-color:#e6f092'
                                        class='text-black text-lg bold text-center bg-teal-800'>
                                        {{ $nombresubcuenta }}</td>
                                </tr>

                                <tr>

                                    <th scope="row" class='text-center'>{{ $cont = $cont + 1 }}</th>
                                    <th scope="row" class='text-center'>{{ $asi->id }}</th>
                                    <td class='text-center'>
                                        {{ \Carbon\Carbon::parse($asi->fec_asiento)->format('d-m-Y') }}
                                    </td>
                                    <td class='text-center'>{{ $asi->factura }}</td>
                                    <td class='text-center'>{{ $asi->recibo }}</td>
                                    <td class='text-center'>{{ $asi->monto_bs }}</td>
                                    <td class='text-center'>{{ $asi->monto_sus }}</td>
                                </tr>

                                @php
                                    $sumatotalbs += $asi->monto_bs;
                                    $sumatotalsus += $asi->monto_sus;
                                    $montobs += $asi->monto_bs;
                                    $montosus += $asi->monto_sus;
                                    $sumasubcuentabs = $asi->monto_bs;
                                    $sumasubcuentasus = $asi->monto_bs;
                                    $subcuentaidactual = $asi->sub_cuenta;

                                @endphp
                                @if ($loop->last)
                                    <tr>
                                        <td colspan="5"
                                            style='font-weight:bold;text-align:right;background-color:#d6aa31'class='bg-red-700 text-black text-right text-lg bold'>
                                            TOTAL
                                            SUBCUENTA</td>
                                        <td colspan="1"
                                            style='font-weight:bold;text-align:right;background-color:#d6aa31'
                                            class=' bg-red-700 text-black text-lg bold text-center'>
                                            {{ number_format($sumasubcuentabs, 2) }}</td>

                                        <td colspan="1"
                                            style='font-weight:bold;text-align:right;background-color:#d6aa31'
                                            class='bg-red-700 text-black text-lg bold text-center'>
                                            {{ number_format($sumasubcuentasus, 2) }}
                                        </td>

                                    </tr>
                                    <tr>
                                        <td colspan="4"
                                            style='font-weight:bold;text-align:right;background-color:#348a7b'
                                            class='bg-sky-900'></td>
                                        <td colspan="1"
                                            style='font-weight:bold;text-align:right;background-color:#348a7b'
                                            class='bg-sky-900 text-black text-right text-lg bold'>TOTAL
                                        </td>
                                        <td colspan="1"
                                            style='font-weight:bold;text-align:right;background-color:#348a7b'
                                            class='bg-sky-900 text-black text-lg bold text-center'>
                                            {{ number_format($montobs, 2) }}</td>
                                        <td colspan="1"
                                            style='font-weight:bold;text-align:right;background-color:#348a7b'
                                            class='bg-sky-900  text-black text-lg bold text-center'>
                                            {{ number_format($montosus, 2) }}</td>
                                    </tr>
                                @endif
                            @elseif($cuentaidactual !== $asi->cuenta)
                                <tr>
                                    <td colspan="5"
                                        style='font-weight:bold;text-align:right;background-color:#d6aa31'
                                        class='bg-red-700 text-black text-right text-lg bold'>TOTAL
                                        SUBCUENTA</td>
                                    <td colspan="1"
                                        style='font-weight:bold;text-align:right;background-color:#d6aa31'
                                        class=' bg-red-700 text-black text-lg bold text-center'>
                                        {{ number_format($sumasubcuentabs, 2) }}</td>

                                    <td colspan="1"
                                        style='font-weight:bold;text-align:right;background-color:#d6aa31'
                                        class='bg-red-700 text-black text-lg bold text-center'>
                                        {{ number_format($sumasubcuentasus, 2) }}
                                    </td>

                                </tr>
                                <tr>
                                    <td colspan="4"
                                        style='font-weight:bold;text-align:right;background-color:#348a7b'
                                        class='bg-sky-900'></td>
                                    <td colspan="1"
                                        style='font-weight:bold;text-align:right;background-color:#348a7b'
                                        class='bg-sky-900 text-black text-right text-lg bold'>TOTAL
                                    </td>
                                    <td colspan="1"
                                        style='font-weight:bold;text-align:right;background-color:#348a7b'
                                        class='bg-sky-900 text-black text-lg bold text-center'>
                                        {{ number_format($montobs, 2) }}</td>
                                    <td colspan="1"
                                        style='font-weight:bold;text-align:right;background-color:#348a7b'
                                        class='bg-sky-900  text-black text-lg bold text-center'>
                                        {{ number_format($montosus, 2) }}</td>
                                </tr>
                                @php
                                    $montobs = 0;
                                    $montosus = 0;
                                    $sumasubcuentabs = 0;
                                    $sumasubcuentasus = 0;

                                @endphp
                                @php
                                    $nombrecuenta = $cuentaux->getCuenta($asi->cuenta);
                                    $nombresubcuenta = $subcuentaux->getSubcuenta($asi->sub_cuenta);
                                    $nombrecuentas[$contadorcuentas] = $nombrecuenta;
                                    $contadorcuentas = $contadorcuentas + 1;
                                @endphp
                                <tr>
                                    <td colspan="7"
                                        style='font-weight:bold;text-align:center;background-color:#a5640ee0'>
                                        {{ $nombrecuenta }}</td>
                                </tr>
                                <tr>
                                    <td colspan="7" style='text-align:center;background-color:#e6f092'
                                        class='text-black text-lg bold text-center bg-teal-800'>
                                        {{ $nombresubcuenta }}</td>
                                </tr>

                                <tr>

                                    <th scope="row" class='text-center'>{{ $cont = $cont + 1 }}</th>
                                    <th scope="row" class='text-center'>{{ $asi->id }}</th>
                                    <td class='text-center'>
                                        {{ \Carbon\Carbon::parse($asi->fec_asiento)->format('d-m-Y') }}
                                    </td>
                                    <td class='text-center'>{{ $asi->factura }}</td>
                                    <td class='text-center'>{{ $asi->recibo }}</td>
                                    <td class='text-center'>{{ $asi->monto_bs }}</td>
                                    <td class='text-center'>{{ $asi->monto_sus }}</td>
                                </tr>

                                @php
                                    $sumatotalbs += $asi->monto_bs;
                                    $sumatotalsus += $asi->monto_sus;
                                    $montobs += $asi->monto_bs;
                                    $montosus += $asi->monto_sus;
                                    $sumasubcuentabs += $asi->monto_bs;
                                    $sumasubcuentasus += $asi->monto_sus;
                                    $cuentaidactual = $asi->cuenta;
                                    $subcuentaidactual = $asi->sub_cuenta;

                                @endphp
                                @if ($loop->last)
                                    <tr>
                                        <td colspan="5"
                                            style='font-weight:bold;text-align:right;background-color:#d6aa31'
                                            class='bg-red-700 text-black text-right text-lg bold'>TOTAL
                                            SUBCUENTA</td>
                                        <td colspan="1"
                                            style='font-weight:bold;text-align:right;background-color:#d6aa31'
                                            class=' bg-red-700 text-black text-lg bold text-center'>
                                            {{ number_format($sumasubcuentabs, 2) }}</td>

                                        <td colspan="1"
                                            style='font-weight:bold;text-align:right;background-color:#d6aa31'
                                            class='bg-red-700 text-black text-lg bold text-center'>
                                            {{ number_format($sumasubcuentasus, 2) }}
                                        </td>

                                    </tr>
                                    <tr>
                                        <td colspan="4"
                                            style='font-weight:bold;text-align:right;background-color:#348a7b'
                                            class='bg-sky-900'></td>
                                        <td colspan="1"
                                            style='font-weight:bold;text-align:right;background-color:#348a7b'class='bg-sky-900 text-black text-right text-lg bold'>
                                            TOTAL
                                        </td>
                                        <td colspan="1"
                                            style='font-weight:bold;text-align:right;background-color:#348a7b'
                                            class='bg-sky-900 text-black text-lg bold text-center'>
                                            {{ number_format($montobs, 2) }}</td>
                                        <td colspan="1"
                                            style='font-weight:bold;text-align:right;background-color:#348a7b'
                                            class='bg-sky-900  text-black text-lg bold text-center'>
                                            {{ number_format($montosus, 2) }}</td>
                                    </tr>
                                @endif
                            @endif
                        @endforeach|
                        <tr>
                            <td colspan="3" style='font-weight:bold;text-align:right;background-color:#458ab3'
                                class='bg-green-700'></td>
                            <td colspan="2"
                                style='font-weight:bold;text-align:right;background-color:#458ab3'class='bg-green-700 text-black text-right text-lg bold'>
                                TOTAL GASTOS EN
                                EL
                                PERÍODO</td>
                            <td colspan="1" style='font-weight:bold;text-align:right;background-color:#458ab3'
                                class='bg-green-700 text-black text-lg bold text-center'>
                                {{ number_format($sumatotalbs, 2) }}</td>
                            <td colspan="1"
                                style='font-weight:bold;text-align:right;background-color:#458ab3'class='bg-green-700  text-black text-lg bold text-center'>
                                {{ number_format($sumatotalsus, 2) }}</td>
                        </tr>
                        @php
                            $sumatotalbs = 0;
                            $sumatotalsus = 0;
                            $montobs = 0;
                            $montosus = 0;
                            $sumasubcuentabs = 0;
                            $sumasubcuentasus = 0;

                        @endphp
                    </tbody>
                </table>

            </div><!--- fin div card-body---->

        </div><!---fin class card---->

    </div><!---fin div principal---->

    <div id='secundario' style=' width:720px; height:820px;margin-top:20px'>

        <table style=' width-720px;border:none '>
            <thead class='border-none'>
                <tr>
                    <td style='font-weight:bold;text-align:center;border:none' colspan="3">HOJA DE PRESENTACIÓN DE
                        ESTADO DE
                        RESULTADOS
                    </td>
                </tr>

                <tr>
                    <td style='font-weight:bold;text-align:center;border:none' colspan="3">FUNDACIÓN ARCA DE
                        RESCATE DE LOS NIÑOS
                    </td>
                </tr>
                <tr>
                    <td style='font-weight:bold;text-align:left;border:none;text-decoration:underline'colspan="3">
                        {{ $nombremes }} &nbsp;{{ $anio }}</td>
                </tr>

                <tr>
                    <td style='font-weight:bold;text-align:center;border:none'colspan="3">HOJA DE TRABAJO AUDITADO</td>
                </tr>
                <tr>
                    <td style='font-weight:bold;text-align:center;border:none'colspan="3">Del &nbsp;
                        {{ \Carbon\Carbon::parse($fecinicio)->format('d-m-Y') }}&nbsp;al&nbsp;
                        {{ \Carbon\Carbon::parse($fecfin)->format('d-m-Y') }} </td>
                </tr>
                <tr class='font-bold text-center'>
                    <td style='font-weight:bold;border:none;text-align:center'colspan="3">Expresado en Dólares
                        Estadounidenses</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style='text-decoration:underline;font-weight:bold;text-align:left;border:none'>INGRESOS</td>
                    <td style='border:solid 2px'>
                        {{ number_format($sumacreditos + $interescredito, 2, '.', ',') }}</td>
                    <td style='border:solid 2px'>
                        {{ number_format($sumacreditos + $interescredito, 2, '.', ',') }}</td>
                </tr>
                <tr>
                    <td style='text-decoration:underline;text-align:center;font-weight:bold;border:none'>Ingresos del
                        mes de
                        {{ $nombremes }} del {{ $anio }}</td>
                    <td class='bg-amber-300 w-64 text-bold text-right ' style='border:solid 2px'>
                        {{ number_format($sumacreditos, 2, '.', ',') }}</td>
                    <td style='border:none'></td>

                </tr>
                <tr>
                    <td style='border:none;text-align:center'>Saldo inicial al mes de {{ $nombremes }} del
                        {{ $anio }}</td>
                    <td style="text-align: right;border:none">{{ number_format($saldo, 2, '.', ',') }}</td>
                </tr>
                @foreach ($detallecreditos as $detalle)
                    <tr>

                        <td style='border:none;text-align:center'>Depósito bancario en fecha
                            &nbsp;{{ \Carbon\Carbon::parse($detalle->fecha)->format('d-m-Y') }}</td>
                        <td style='text-align:right;border:none'>{{ number_format($detalle->credito, 2, '.', ',') }}
                        </td>
                        <td style='border:none'></td>
                    </tr>
                @endforeach
                <tr>
                    <td style='text-decoration:underline;border:none;font-weight:bold'>OTROS INGRESOS</td>
                    <td style='border:none'></td>
                    <td style='border:none'></td>
                    <td style='border:none'></td>
                </tr>
                <tr>
                    <td style='border:none;text-align:center'>Intereses al mes de {{ $nombremes }} del
                        {{ $anio }}</td>
                    <td style='border:none'>{{ $interescredito }}</td>
                    <td style='border:none'></td>
                </tr>
                <tr>
                    <td style='text-align:center:border:none'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Devolucion de fondos al
                        cierre
                        mensual</br>(exposición)</td>
                    <td style="border:none;text-align:right">{{ $interescredito }}</td>
                    <td style='border:none'></td>
                </tr>
                <tr>
                    <td style='font-weight:bold;text-decoration:underline;border:none ' colspan="1">EGRESOS</td>
                    <td class='bg-red-700 w-64 text-bold text-right' style='border:solid 2px'>
                        {{ number_format($sumacreditos + $interescredito, 2, '.', ',') }}</td>
                    <td style='border:solid 2px'>
                        {{ number_format($sumacreditos + $interescredito, 2, '.', ',') }}</td>
                </tr>

                <tr>
                    <td style='text-decoration:underline;text-align:center;border:none;font-weight:bold'>Gastos
                        operativos
                        &nbsp; {{ $nombremes }} del {{ $anio }}</td>
                    <td style='border:solid 2px;text-align:right'>
                        {{ number_format($gastosoperativos, 2, '.', ',') }}</td>
                    <td style='border:none'></td>

                </tr>
                @foreach ($resultados as $fila)
                    <tr>
                        <td style='text-align:left;border:none'>{{ $fila->cuenta_nombre }}</td>
                        <td style='text-align:right;border:none'>
                            {{ number_format($fila->total, 2, '.', ',') }}
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td style='text-decoration:underline;border:none;text-align:left'>SALDO ESTADO DE
                        INGRESOS
                        Y SALDOS</td>
                    <td style='text-decoration:underline;border:none;text-align:left'></td>
                    <td style='border:none;text-align:right;border:solid 2px'>
                        {{ number_format($sumacreditos - $sumadebitostotales, 2, '.', ',') }}</td>
                </tr>
                <tr>
                    <td style='text-decoration:underline;border:none;text-align:left'>VARIACION</td>
                    <td style='text-decoration:underline;border:none;text-align:left'></td>
                    <td style='border:none;text-align:right;border:solid 2px'>
                        {{ number_format($sumacreditos - $sumadebitostotales - $saldofinal, 2, '.', ',') }}</td>
                </tr>
                <tr>
                    <td style='text-decoration:underline;border:none'>SALDO SEGÚN EXTRACTO
                        BANCARIO</td>
                    <td style='border:none'></td>
                    <td style='border:none;text-align:right;border:solid 2px'>
                        {{ number_format($saldofinal, 2, '.', ',') }}
                    </td>
                </tr>
            </tbody>
        </table>



        <div class="contenedor">
            <div class="caja">
                <div class="contenido">
                    <div>Lic. Aud. Silvia Aguilar García</div>
                    <div>CADB 19836</div>
                    <div>CDA-16-MM85</div>
                </div>
            </div>

            <div class="espaciador"></div>

            <div class="caja">
                <div class="contenido">
                    <div>Ing. Javier Fidel Guillén Escalera</div>
                    <div>c.i.997213 c.b.a</div>
                    <div>Representante Legal</div>
                </div>
            </div>
        </div>



    </div><!---fin div secundario----->
</body>

</html>


@extends('layouts.app')
@section('content')
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
    </style>

    <div><!---div principal--->
        <div class='card'>
            <div class='mb-2'>

                <a href="{{ route('imprimirEstadoDolares') }}"
                    class="btn btn-primary w-36 h-12 text-white bold text-md text-center bg-sky-900 rounded mr-4"
                    style='float:right'>
                    Imprimir Reporte
                </a>
            </div>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card-body mt-[-15px]">

                <table class="table table-striped mb-2 pagination  mx-auto mt-0">

                    <tbody>
                        <thead>

                            <tr colspan='7'>
                                <div class='card-header bg-sky-900 text-black bold text-center w-'>
                                    REPORTE GENERAL DE GASTOS POR CUENTA, SUBCUENTA PERIODO
                                    {{ \Carbon\Carbon::parse($fecinicio)->format('d-m-Y') }} &nbsp;
                                    {{ \Carbon\Carbon::parse($fecfin)->format('d-m-Y') }}

                                </div>
                            </tr>
                            <tr class='bg-sky-900 text-black bold'>
                                <th scope="col" class='text-center w-8 text-black'>Item #</th>
                                <th scope="col" class='text-center w-8 text-black'>Num. Asiento</th>
                                <th scope="col" class='text-center w-24 text-black'>Fecha</th>
                                <th scope="col" class='text-center w-24 text-black'>Factura</th>
                                <th scope="col" class='text-center w-24 text-black'>Recibo</th>
                                <th scope="col" class='text-center w-48 text-black'>Importe Bs.</th>
                                <th scope="col" class='text-center w-48 text-black'>Importe $us</th>

                            </tr>
                        </thead>
                        
                        @foreach ($asientos as $asi)
                            @if ($loop->first)
                                @php
                                    $nombrecuenta = $cuentaux->getCuenta($asi->cuenta);
                                    $nombresubcuenta = $subcuentaux->getSubcuenta($asi->sub_cuenta);
                                    $nombrecuentas[$contadorcuentas] = $nombrecuenta;
                                    $contadorcuentas = $contadorcuentas + 1;
                                @endphp
                                <tr>
                                    <td colspan="7" class='text-black text-lg bold text-center !bg-neutral-700'>
                                        {{ $nombrecuenta }}</td>
                                </tr>
                                <tr>
                                    <td colspan="7" class='text-black text-lg bold text-center !bg-teal-800'>
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
                                        <td colspan="5" class='bg-red-700 text-black text-right text-lg bold'>TOTAL
                                            SUBCUENTA</td>
                                        <td colspan="1" class=' bg-red-700 text-black text-lg bold text-center'>
                                            {{ number_format($sumasubcuentabs, 2) }}</td>

                                        <td colspan="1" class='bg-red-700 text-black text-lg bold text-center'>
                                            {{ number_format($sumasubcuentasus, 2) }}
                                        </td>

                                    </tr>
                                    <tr>
                                        <td colspan="4" class='bg-sky-900'></td>
                                        <td colspan="1" class='bg-sky-900 text-black text-right text-lg bold'>TOTAL</td>
                                        <td colspan="1" class='bg-sky-900 text-black text-lg bold text-center'>
                                            {{ number_format($montobs, 2) }}</td>
                                        <td colspan="1" class='bg-sky-900  text-black text-lg bold text-center'>
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
                                        <td colspan="5" class='bg-red-700 text-black text-right text-lg bold'>TOTAL
                                            SUBCUENTA</td>
                                        <td colspan="1" class=' bg-red-700 text-black text-lg bold text-center'>
                                            {{ number_format($sumasubcuentabs, 2) }}</td>

                                        <td colspan="1" class='bg-red-700 text-black text-lg bold text-center'>
                                            {{ number_format($sumasubcuentasus, 2) }}
                                        </td>

                                    </tr>
                                    <tr>
                                        <td colspan="4" class='bg-sky-900'></td>
                                        <td colspan="1" class='bg-sky-900 text-black text-right text-lg bold'>TOTAL</td>
                                        <td colspan="1" class='bg-sky-900 text-black text-lg bold text-center'>
                                            {{ number_format($montobs, 2) }}</td>
                                        <td colspan="1" class='bg-sky-900  text-black text-lg bold text-center'>
                                            {{ number_format($montosus, 2) }}</td>
                                    </tr>
                                @endif
                            @elseif($cuentaidactual == $asi->cuenta && $subcuentaidactual !== $asi->sub_cuenta)
                                <tr>
                                    <td colspan="5" class='!bg-red-700 text-black text-right text-lg bold'>TOTAL
                                        SUBCUENTA</td>
                                    <td colspan="1" class='! bg-red-700 text-black text-lg bold text-center'>
                                        {{ number_format($sumasubcuentabs, 2) }}</td>

                                    <td colspan="1" class='!bg-red-700 text-black text-lg bold text-center'>
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
                                    <td colspan="7" class='text-black text-lg bold text-center bg-teal-800'>
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
                                    $subcuentaidactual = $asi->sub_cuenta;

                                @endphp
                                @if ($loop->last)
                                    <tr>
                                        <td colspan="5" class='bg-red-700 text-black text-right text-lg bold'>TOTAL
                                            SUBCUENTA</td>
                                        <td colspan="1" class=' bg-red-700 text-black text-lg bold text-center'>
                                            {{ number_format($sumasubcuentabs, 2) }}</td>

                                        <td colspan="1" class='bg-red-700 text-black text-lg bold text-center'>
                                            {{ number_format($sumasubcuentasus, 2) }}
                                        </td>

                                    </tr>
                                    <tr>
                                        <td colspan="4" class='bg-sky-900'></td>
                                        <td colspan="1" class='bg-sky-900 text-black text-right text-lg bold'>TOTAL
                                        </td>
                                        <td colspan="1" class='bg-sky-900 text-black text-lg bold text-center'>
                                            {{ number_format($montobs, 2) }}</td>
                                        <td colspan="1" class='bg-sky-900  text-black text-lg bold text-center'>
                                            {{ number_format($montosus, 2) }}</td>
                                    </tr>
                                @endif
                            @elseif($cuentaidactual !== $asi->cuenta)
                                <tr>
                                    <td colspan="5" class='bg-red-700 text-black text-right text-lg bold'>TOTAL
                                        SUBCUENTA</td>
                                    <td colspan="1" class=' bg-red-700 text-black text-lg bold text-center'>
                                        {{ number_format($sumasubcuentabs, 2) }}</td>

                                    <td colspan="1" class='bg-red-700 text-black text-lg bold text-center'>
                                        {{ number_format($sumasubcuentasus, 2) }}
                                    </td>

                                </tr>
                                <tr>
                                    <td colspan="4" class='bg-sky-900'></td>
                                    <td colspan="1" class='bg-sky-900 text-black text-right text-lg bold'>TOTAL</td>
                                    <td colspan="1" class='bg-sky-900 text-black text-lg bold text-center'>
                                        {{ number_format($montobs, 2) }}</td>
                                    <td colspan="1" class='bg-sky-900  text-black text-lg bold text-center'>
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
                                    <td colspan="7" class='text-black text-lg bold text-center bg-neutral-700'>
                                        {{ $nombrecuenta }}</td>
                                </tr>
                                <tr>
                                    <td colspan="7" class='text-black text-lg bold text-center bg-teal-800'>
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
                                        <td colspan="5" class='bg-red-700 text-black text-right text-lg bold'>TOTAL
                                            SUBCUENTA</td>
                                        <td colspan="1" class=' bg-red-700 text-black text-lg bold text-center'>
                                            {{ number_format($sumasubcuentabs, 2) }}</td>

                                        <td colspan="1" class='bg-red-700 text-black text-lg bold text-center'>
                                            {{ number_format($sumasubcuentasus, 2) }}
                                        </td>

                                    </tr>
                                    <tr>
                                        <td colspan="4" class='bg-sky-900'></td>
                                        <td colspan="1" class='bg-sky-900 text-black text-right text-lg bold'>TOTAL
                                        </td>
                                        <td colspan="1" class='bg-sky-900 text-black text-lg bold text-center'>
                                            {{ number_format($montobs, 2) }}</td>
                                        <td colspan="1" class='bg-sky-900  text-black text-lg bold text-center'>
                                            {{ number_format($montosus, 2) }}</td>
                                    </tr>
                                @endif
                            @endif
                        @endforeach|
                        <tr>
                            <td colspan="3" class='bg-green-700'></td>
                            <td colspan="2" class='bg-green-700 text-black text-right text-lg bold'>TOTAL GASTOS EN EL
                                PERÍODO</td>
                            <td colspan="1" class='bg-green-700 text-black text-lg bold text-center'>
                                {{ number_format($sumatotalbs, 2) }}</td>
                            <td colspan="1" class='bg-green-700  text-black text-lg bold text-center'>
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

    <div id='secundario' class='bg-yellow-100 w-[720px] h-[820px]'>

        <table class='table table-borderless border-none border-collapse w-[720px] bg-yellow-100 space-y-*'>
            <thead class='border-none'>
                <tr>
                    <td class='col-span-4 text-center border-none font-bold'>HOJA DE PRESENTACIÓN DE ESTADO DE RESULTADOS
                    </td>
                </tr>

                <tr>
                    <td class='col-span-4 text-center font-bold bg-amber-200'>FUNDACIÓN ARCA DE RESCATE DE LOS NIÑOS</td>
                </tr>
                <tr>
                    <td class='font-bold'>{{ $nombremes }} &nbsp;{{ $anio }}</td>
                </tr>

                <tr class='font-bold text-center'>
                    <td class='font-bold text-center'>HOJA DE TRABAJO AUDITADO</td>
                </tr>
                <tr class='font-bold text-center'>
                    <td class='font-bold text-center'>Del &nbsp;
                        {{ \Carbon\Carbon::parse($fecinicio)->format('d-m-Y') }}&nbsp;al&nbsp;
                        {{ \Carbon\Carbon::parse($fecfin)->format('d-m-Y') }} </td>
                </tr>
                <tr class='font-bold text-center'>
                    <td class='font-bold text-center col-span-2'>Expresado en Dólares Estadounidenses</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class='font-bold underlined col-span-2' style='text-decoration:underline'>INGRESOS</td>
                    <td class='bg-red-700 w-64 text-bold text-right' style='border:solid 2px'>
                        {{ number_format($sumacreditos + $interescredito, 2, '.', ',') }}</td>
                    <td class='w-64 text-bold text-right bg-neutral-500'>
                        {{ number_format($sumacreditos + $interescredito, 2, '.', ',') }}</td>
                </tr>
                <tr>
                    <td class='col-span-2 text-center font-bold' style='text-decoration:underline'>Ingresos del mes de
                        {{ $nombremes }} del {{ $anio }}</td>
                    <td class='bg-amber-300 w-64 text-bold text-right ' style='border:solid 2px'>
                        {{ number_format($sumacreditos, 2, '.', ',') }}</td>
                    <td class='w-64'></td>

                </tr>
                <tr>
                    <td class='col-span-2 text-center'>Saldo inicial al mes de {{ $nombremes }} del
                        {{ $anio }}</td>
                    <td style="text-align: right;">{{ number_format($saldo, 2, '.', ',') }}</td>
                </tr>
                @foreach ($detallecreditos as $detalle)
                    <tr class="hover:bg-gray-100">

                        <td class="p-2 border-b text-center">Depósito bancario en fecha
                            &nbsp;{{ \Carbon\Carbon::parse($detalle->fecha)->format('d-m-Y') }}</td>
                        <td class="p-2 border-b text-right">{{ number_format($detalle->credito, 2, '.', ',') }}</td>
                        <td class='w-64'></td>
                    </tr>
                @endforeach
                <tr>
                    <td class='font-bold underlined col-span-2' style='text-decoration:underline'>OTROS INGRESOS</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td class=' text-center'>Intereses al mes de {{ $nombremes }} del {{ $anio }}</td>
                    <td class="p-2 border-b text-right">{{ $interescredito }}</td>
                    <td class='w-64'></td>
                </tr>
                <tr>
                    <td class=' text-center'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Devolucion de fondos al cierre
                        mensual</br>(exposición)</td>
                    <td class="p-2 border-b text-right">{{ $interescredito }}</td>
                    <td class='w-64'></td>
                </tr>
                <tr>
                    <td class='font-bold underlined col-span-2' style='text-decoration:underline'>EGRESOS</td>
                    <td class='bg-red-700 w-64 text-bold text-right' style='border:solid 2px'>
                        {{ number_format($sumacreditos + $interescredito, 2, '.', ',') }}</td>
                    <td class='w-64 text-bold text-right bg-neutral-500'>
                        {{ number_format($sumacreditos + $interescredito, 2, '.', ',') }}</td>
                </tr>

                <tr>
                    <td class='col-span-2 text-center font-bold' style='text-decoration:underline'>Gastos operativos
                        &nbsp; {{ $nombremes }} del {{ $anio }}</td>
                    <td class='bg-amber-300 w-64 text-bold text-right ' style='border:solid 2px'>
                        {{ number_format($gastosoperativos, 2, '.', ',') }}</td>
                    <td class='w-64'></td>

                </tr>
                @foreach ($resultados as $fila)
                    <tr>
                        <td class="border border-gray-300 px-4 py-2">{{ $fila->cuenta_nombre }}</td>
                        <td class="border border-gray-300 px-4 py-2 text-right">
                            {{ number_format($fila->total, 2, '.', ',') }}
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td class='font-bold underlined col-span-2' style='text-decoration:underline'>SALDO ESTADO DE INGRESOS
                        Y SALDOS</td>
                    <td class=' text-bold text-right'></td>
                    <td class='w-64 text-bold text-right bg-neutral-500'>
                        {{ number_format($sumacreditos - $sumadebitostotales, 2, '.', ',') }}</td>
                </tr>
                <tr>
                    <td class='font-bold text-sm text-center font-small underlined col-span-2'
                        style='text-decoration:underline'>VARIACION</td>
                    <td class=' text-bold text-right'></td>
                    <td class='w-64 text-bold text-sm text-right bg-neutral-500'>
                        {{ number_format($sumacreditos - $sumadebitostotales - $saldofinal, 2, '.', ',') }}</td>
                </tr>
                <tr>
                    <td class='font-bold underlined col-span-2' style='text-decoration:underline'>SALDO SEGÚN EXTRACTO
                        BANCARIO</td>
                    <td class=' text-bold text-right'></td>
                    <td class='w-64 text-bold text-right bg-neutral-500'>{{ number_format($saldofinal, 2, '.', ',') }}
                    </td>
                </tr>
            </tbody>
        </table>

        <div id='firmas' class='grid grid-cols-2 flex mt-48'>

            <div class='grid grid-rows-3 grid-cols-1 ml-12 '>

                <div class='text-center mt-18'>
                    Lic. Aud. Silvia Aguilar García
                </div>
                <div class='text-center'>

                    CADB 19836

                </div>
                <div class='text-center'>
                    CDA-16-MM85
                </div>
            </div>


            <div class='grid grid-rows-3 grid-cols-1 '>

                <div class='text-center mt-18'>
                    Ing. Javier Fidel Guillén Escalera
                </div>
                <div class='text-center'>
                    c.i.997213 c.b.a
                </div>
                <div class='text-center'>
                    Representante Legal
                </div>
            </div>

            <div class='w-96'>

            </div>



        </div><!--- fin div firmas --->

    </div><!---fin div secundario----->
@endsection

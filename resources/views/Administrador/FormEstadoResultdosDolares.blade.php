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

        /* 🎨 SOLO ESTILO */
        .cabecera-cuenta {
            background-color: #374151 !important;
            color: white !important;
            font-weight: bold;
        }

        .cabecera-subcuenta {
            background-color: #0f766e !important;
            color: white !important;
            font-weight: bold;
        }

        .total-subcuenta {
            background-color: #b91c1c !important;
            color: white !important;
            font-weight: bold;
        }

        .total-general {
            background-color: #1e3a8a !important;
            color: white !important;
            font-weight: bold;
        }

        .total-final {
            background-color: #15803d !important;
            color: white !important;
            font-weight: bold;
        }

        @media print {
            .main-sidebar,
            .sidebar,
            aside.sidebar,
            .main-header,
            .navbar,
            .main-footer,
            .control-sidebar,
            .btn,
            #btnImprimir,
            #btnReporte {
                display: none !important;
            }

            .content-wrapper,
            .content {
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
            }
        }
    </style>

    <div>
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
                                <th class='text-center'>Item #</th>
                                <th class='text-center'>Num. Asiento</th>
                                <th class='text-center'>Fecha</th>
                                <th class='text-center'>Factura</th>
                                <th class='text-center'>Recibo</th>
                                <th class='text-center'>Importe Bs.</th>
                                <th class='text-center'>Importe $us</th>
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
                                    <td colspan="7" class='cabecera-cuenta text-center'>
                                        {{ $nombrecuenta }}
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="7" class='cabecera-subcuenta text-center'>
                                        {{ $nombresubcuenta }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class='text-center'>{{ $cont = $cont + 1 }}</th>
                                    <th class='text-center'>{{ $asi->id }}</th>
                                    <td class='text-center'>{{ \Carbon\Carbon::parse($asi->fec_asiento)->format('d-m-Y') }}</td>
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
                            @endif
                        @endforeach

                        <tr>
                            <td colspan="3" class='total-final'></td>
                            <td colspan="2" class='total-final text-right'>TOTAL GASTOS EN EL PERÍODO</td>
                            <td class='total-final text-center'>{{ number_format($sumatotalbs, 2) }}</td>
                            <td class='total-final text-center'>{{ number_format($sumatotalsus, 2) }}</td>
                        </tr>

                    </tbody>
                </table>

            </div>
        </div>
    </div>

    <!-- 🔥 ESTE ES EL DIV SECUNDARIO COMPLETO (NO OMITIDO) -->
    <div id='secundario' class='bg-yellow-100 w-[720px] h-[820px]'>

        <table class='table table-borderless border-none border-collapse w-[720px] bg-yellow-100'>
            <thead>
                <tr>
                    <td class='text-center font-bold'>HOJA DE PRESENTACIÓN DE ESTADO DE RESULTADOS</td>
                </tr>

                <tr>
                    <td class='text-center font-bold bg-amber-200'>FUNDACIÓN ARCA DE RESCATE DE LOS NIÑOS</td>
                </tr>

                <tr>
                    <td class='font-bold'>{{ $nombremes }} {{ $anio }}</td>
                </tr>

                <tr class='font-bold text-center'>
                    <td>HOJA DE TRABAJO AUDITADO</td>
                </tr>

                <tr class='font-bold text-center'>
                    <td>
                        Del {{ \Carbon\Carbon::parse($fecinicio)->format('d-m-Y') }}
                        al {{ \Carbon\Carbon::parse($fecfin)->format('d-m-Y') }}
                    </td>
                </tr>

                <tr class='font-bold text-center'>
                    <td>Expresado en Dólares Estadounidenses</td>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td class='font-bold'>INGRESOS</td>
                    <td class='text-right bg-neutral-500'>
                        {{ number_format($sumacreditos + $interescredito+$saldo, 2) }}
                    </td>
                </tr>

                <tr>
                    <td class='font-bold'>EGRESOS</td>
                    <td class='text-right bg-neutral-500'>
                        {{ number_format($gastosoperativos, 2) }}
                    </td>
                </tr>

                <tr>
                    <td class='font-bold'>SALDO FINAL</td>
                    <td class='text-right bg-neutral-500'>
                        {{ number_format(($sumacreditos+$interescredito+$saldo)-$gastosoperativos, 2) }}
                    </td>
                </tr>
            </tbody>
        </table>

    </div>
@endsection

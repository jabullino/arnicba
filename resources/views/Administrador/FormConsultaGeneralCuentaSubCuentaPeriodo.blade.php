@extends('layouts.app')
@section('content')
<style>
    table, th, td {
        border: 1px solid black;
        border-collapse: collapse;
        padding: 8px;
    }

    @media print {
        #btnImprimir {
            display: none;
        }
    }

    /* ------------------ Responsive ------------------ */
    @media (max-width: 1024px) {
        table {
            font-size: 0.9rem;
        }
        th, td {
            padding: 6px;
        }
    }

    @media (max-width: 768px) {
        table {
            display: block;
            overflow-x: auto;
            width: 100%;
        }
        th, td {
            padding: 5px;
            font-size: 0.85rem;
        }
        .card-header {
            font-size: 1rem !important;
            text-align: center;
        }
    }

    @media (max-width: 480px) {
        table {
            display: block;
            overflow-x: auto;
            width: 100%;
        }
        th, td {
            padding: 4px;
            font-size: 0.75rem;
        }
        .card-header {
            font-size: 0.9rem !important;
            text-align: center;
        }
    }
</style>

<div><!---div principal--->
    <div class='card'>
        <div class="card-body mt-[-15px]">
            
            <table class="table table-striped mb-2 pagination mx-auto mt-0">
                <tbody>
                    <thead>
                        <tr colspan='7'>
                            <div class='card-header bg-sky-900 text-black bold text-center w-full'>
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
                        <tr>
                            <th scope="row" class='text-center'>{{ $cont = $cont + 1 }}</th>
                            <th scope="row" class='text-center'>{{ $asi->id }}</th>
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
                        @endphp
                    @endforeach

                    <tr>
                        <td colspan="3" class='bg-green-700'></td>
                        <td colspan="2" class='bg-green-700 text-black text-right text-lg bold'>TOTAL GASTOS EN EL PERÍODO</td>
                        <td colspan="1" class='bg-green-700 text-black text-lg bold text-center'>
                            {{ number_format($sumatotalbs, 2) }}</td>
                        <td colspan="1" class='bg-green-700 text-black text-lg bold text-center'>
                            {{ number_format($sumatotalsus, 2) }}</td>
                    </tr>

                    @php
                        $sumatotalbs = 0;
                        $sumatotalsus = 0;
                        $montobs = 0;
                        $montosus = 0;
                    @endphp

                </tbody>
            </table>

            <div class="text-center mt-2">
                <button id="btnImprimir" onclick="window.print()" class="bg-sky-900 text-white bold p-2 rounded">Imprimir</button>
            </div>

        </div><!--- fin div card-body---->
    </div><!---fin class card---->
</div><!---fin div principal---->
@endsection

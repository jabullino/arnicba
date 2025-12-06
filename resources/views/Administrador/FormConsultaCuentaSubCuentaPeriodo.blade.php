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
        #encabezado {
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
        #encabezado {
            font-size: 0.9rem !important;
            text-align: center;
        }
    }
</style>

<div><!---div principal--->
    <div class='card'>
        <div class="card-body mt-[-15px]">
            <div class='card-header bg-sky-900 text-white font-bold text-center w-full'>
                REPORTE DE GASTOS POR CUENTA SUBCUENTA Y PERÍODO EN FECHAS
                {{ \Carbon\Carbon::parse($fecinicio)->format('d-m-Y') }} &nbsp; AL &nbsp;
                {{ \Carbon\Carbon::parse($fecfin)->format('d-m-Y') }}
            </div>

            <table class="table table-striped mb-2 pagination mx-auto mt-0">
                <tbody>
                    <thead>
                        <tr colspan='7'>
                            <div class='bg-sky-900 text-black text-2lg bold text-center w-full' id='encabezado'>
                                REPORTE DE GASTOS POR CUENTA Y PERÍODO EN FECHAS
                                {{ \Carbon\Carbon::parse($fecinicio)->format('d-m-Y') }} &nbsp; AL &nbsp;
                                {{ \Carbon\Carbon::parse($fecfin)->format('d-m-Y') }}
                            </div>
                        </tr>
                        <tr>
                            <td colspan="7" class='text-black text-lg bold text-center bg-sky-900'>
                                {{ $nombrecuenta }}</td>
                        </tr>
                        <tr>
                            <td colspan="7" class='text-black text-lg bold text-center bg-gray-700'>
                                {{ $nombresubcuenta }}</td>
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
                        @php
                            $montobs += $asi->monto_bs;
                            $montosus += $asi->monto_sus;
                        @endphp
                        <tr>
                            <th scope="row" class='text-center'>{{ $cont = $cont + 1 }}</th>
                            <th scope="row" class='text-center'>{{ $asi->id }}</th>
                            <td class='text-center'>{{ \Carbon\Carbon::parse($asi->fec_asiento)->format('d-m-Y') }}</td>
                            <td class='text-center'>{{ $asi->factura }}</td>
                            <td class='text-center'>{{ $asi->recibo }}</td>
                            <td class='text-center'>{{ $asi->monto_bs }}</td>
                            <td class='text-center'>{{ $asi->monto_sus }}</td>
                        </tr>
                    @endforeach

                    <tr>
                        <td colspan="4" class='bg-gray-700'></td>
                        <td colspan="1" class='bg-gray-700 text-black text-right text-lg bold'>TOTAL</td>
                        <td colspan="1" class='bg-gray-700 text-black text-lg bold text-center'>{{ number_format($montobs, 2) }}</td>
                        <td colspan="1" class='bg-gray-700 text-black text-lg bold text-center'>{{ number_format($montosus, 2) }}</td>
                    </tr>
                </tbody>
            </table>

            <div class="text-center mt-2">
                <button id="btnImprimir" onclick="window.print()" class="bg-sky-900 text-white bold p-2 rounded">Imprimir</button>
            </div>

        </div><!--- fin div card-body---->
    </div><!---fin class card---->
</div><!---fin div principal---->
@endsection

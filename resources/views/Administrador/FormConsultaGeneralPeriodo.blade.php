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
        .card-header {
            font-size: 1.1rem;
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
            font-size: 1rem;
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
            font-size: 0.9rem;
            text-align: center;
        }
    }
</style>

<div><!---div principal--->
    <div class='card'>

        <div class='card-header bg-sky-900 text-black bold text-center'>
            REPORTE GENERAL DE GASTOS POR CUENTA Y SUBCUENTA POR PERÍODO
        </div>
        <div class="card-body">

            <table class="table table-striped mb-2 pagination">
                <tbody>

                    @foreach ($asientos as $asi)
                        {{ Session('anteriorId') }}

                        @if ($asientosaux->verificaId($asi->cuenta)!=0)
                            <thead>
                                <tr class='bg-slate-500 text-white bold'>
                                    <th scope="col" class='text-center w-8 text-black'>Item #</th>
                                    <th scope="col" class='text-center w-8 text-black'>Num. Asiento</th>
                                    <th scope="col" class='text-center text-black'>Fecha</th>
                                    <th scope="col" class='text-center text-black'>Factura</th>
                                    <th scope="col" class='text-center text-black'>Recibo</th>
                                    <th scope="col" class='text-center text-black'>Importe Bs.</th>
                                    <th scope="col" class='text-center text-black'>Importe $us</th>
                                </tr>
                            </thead>    
                            <tr>
                                <td colspan="7" class='bg-sky-900 text-white bold-md text-center'>
                                    {{ $cuentasaux->getCuenta($asi->cuenta) }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="7" class='bg-gray-700 text-white bold-md text-center'>
                                    {{ $subcuentasaux->getSubcuenta($asi->sub_cuenta) }}
                                </td>
                            </tr>
                        @endif

                        <tr>
                            <th scope="row" class='text-center'>{{ $cont=$cont+1 }}</th>
                            <th scope="row" class='text-center'>{{ $asi->id }}</th>
                            <td class='text-center'>{{ \Carbon\Carbon::parse($asi->fec_asiento)->format('d-m-Y') }}</td>
                            <td class='text-center'>{{ $asi->factura }}</td>
                            <td class='text-center'>{{ $asi->recibo }}</td>
                            <td class='text-right'>{{ $asi->monto_bs }}</td>
                            <td class='text-right'>{{ $asi->monto_sus }}</td>
                        </tr>
                    @endforeach

                </tbody>
            </table>

            <div class="text-center mt-2">
                <button id="btnImprimir" onclick="window.print()" class="bg-sky-900 text-white bold p-2 rounded">Imprimir</button>
            </div>

        </div><!--- fin div card-body---->

    </div><!---fin class card---->
</div><!---fin div principal---->
@endsection

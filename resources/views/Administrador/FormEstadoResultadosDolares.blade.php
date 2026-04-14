@include('layouts.app')
@section('content')
<style>
    table, th, td {
        border: 1px solid black;
        border-collapse: collapse;
        padding: 8px;
    }

    @media print {
        .main-sidebar, .sidebar, aside.sidebar,
        .main-header, .navbar,
        .main-footer,
        .control-sidebar,
        .btn, #btnImprimir, #btnReporte {
            display: none !important;
        }

        .content-wrapper, .content {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }
    }
</style>

<div>
    <div class='card'>

        <div class='mb-2'>
            <a href="{{ route('imprimirEstadoBolivianos') }}"
               class="btn btn-primary"
               style='float:right'>
                Imprimir Reporte
            </a>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card-body">

            <table class="table table-striped">

                <thead>
                    <tr>
                        <th colspan="7">
                            <div class='text-center'>
                                REPORTE GENERAL DE GASTOS
                                {{ \Carbon\Carbon::parse($fecinicio)->format('d-m-Y') }} -
                                {{ \Carbon\Carbon::parse($fecfin)->format('d-m-Y') }}
                            </div>
                        </th>
                    </tr>

                    <tr>
                        <th>Item</th>
                        <th>Asiento</th>
                        <th>Fecha</th>
                        <th>Factura</th>
                        <th>Recibo</th>
                        <th>Importe Bs.</th>
                    </tr>
                </thead>

                <tbody>

                @foreach($asientos as $asi)

                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $asi->id }}</td>
                        <td>{{ \Carbon\Carbon::parse($asi->fec_asiento)->format('d-m-Y') }}</td>
                        <td>{{ $asi->factura }}</td>
                        <td>{{ $asi->recibo }}</td>
                        <td>{{ $asi->monto_bs }}</td>
                    </tr>

                @endforeach

                <tr>
                    <td colspan="5" class='text-right'><strong>TOTAL</strong></td>
                    <td>{{ number_format($sumatotalbs, 2) }}</td>
                </tr>

                </tbody>
            </table>

        </div>
    </div>

    <!-- SEGUNDA TABLA CORREGIDA -->

    <div id='secundario'>
        <table class='table'>

            <thead>
                <tr>
                    <td colspan="4" class='text-center font-bold'>
                        HOJA DE PRESENTACIÓN DE ESTADO DE RESULTADOS
                    </td>
                </tr>

                <tr>
                    <td colspan="4" class='text-center font-bold'>
                        FUNDACIÓN ARCA DE RESCATE DE LOS NIÑOS
                    </td>
                </tr>

                <tr>
                    <td>{{ $nombremes }} {{ $anio }}</td>
                </tr>

                <tr>
                    <td class='text-center font-bold'>
                        Del {{ \Carbon\Carbon::parse($fecinicio)->format('d-m-Y') }}
                        al {{ \Carbon\Carbon::parse($fecfin)->format('d-m-Y') }}
                    </td>
                </tr>
            </thead>

            <tbody>

                <tr>
                    <td><strong>INGRESOS</strong></td>
                    <td></td>
                    <td></td>
                    <td class='text-right'>
                        {{ number_format($sumacreditos + $interescredito + $saldo, 2) }}
                    </td>
                </tr>

                @foreach($detallecreditos as $detalle)
                    <tr>
                        <td>
                            Depósito {{ \Carbon\Carbon::parse($detalle->fecha)->format('d-m-Y') }}
                        </td>
                        <td class='text-right'>
                            {{ number_format($detalle->credito, 2) }}
                        </td>
                    </tr>
                @endforeach

                <tr>
                    <td><strong>EGRESOS</strong></td>
                    <td></td>
                    <td></td>
                    <td class='text-right'>
                        {{ number_format($gastosoperativos, 2) }}
                    </td>
                </tr>

                @foreach($resultados as $fila)
                    <tr>
                        <td>{{ $fila->cuenta_nombre }}</td>
                        <td class='text-right'>
                            {{ number_format($fila->total, 2) }}
                        </td>
                    </tr>
                @endforeach

                <tr>
                    <td><strong>SALDO FINAL</strong></td>
                    <td></td>
                    <td></td>
                    <td class='text-right'>
                        {{ number_format($saldofinal, 2) }}
                    </td>
                </tr>

            </tbody>
        </table>
    </div>

</div>

@endsection



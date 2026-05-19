@extends('layouts.app')
@section('content')

<style>

    table,
    th,
    td {

        border: 1px solid black !important;
        border-collapse: collapse !important;
        padding: 8px !important;

    }

    /* ENCABEZADOS PRINCIPALES */
    tr.encabezado-principal td,
    tr.encabezado-principal th {

        background: #1f4e78 !important;
        color: #ffffff !important;
        font-weight: bold !important;
        text-align: center !important;

    }

    /* ENCABEZADOS COLUMNAS */
    tr.encabezado-columnas td,
    tr.encabezado-columnas th {

        background: #4472c4 !important;
        color: #ffffff !important;
        font-weight: bold !important;
        text-align: center !important;

    }

    /* CUENTAS */
    tr.fila-cuenta td {

        background: #d9e2f3 !important;
        color: #000000 !important;
        font-weight: bold !important;
        text-align: center !important;

    }

    /* SUBCUENTAS */
    tr.fila-subcuenta td {

        background: #edf2f9 !important;
        color: #000000 !important;
        font-weight: bold !important;
        text-align: center !important;

    }

    /* SUBTOTALES */
    tr.fila-subtotal td {

        background: #fff2cc !important;
        color: #000000 !important;
        font-weight: bold !important;

    }

    /* TOTALES */
    tr.fila-total td {

        background: #c6e0b4 !important;
        color: #000000 !important;
        font-weight: bold !important;

    }

    /* TOTAL GENERAL */
    tr.fila-total-general td {

        background: #70ad47 !important;
        color: #ffffff !important;
        font-weight: bold !important;

    }

    .centrado {

        text-align: center !important;

    }

    .derecha {

        text-align: right !important;

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

                    <tr class='encabezado-principal'>

                        <th colspan="7">

                            REPORTE GENERAL DE GASTOS

                            {{ \Carbon\Carbon::parse($fecinicio)->format('d-m-Y') }}

                            -

                            {{ \Carbon\Carbon::parse($fecfin)->format('d-m-Y') }}

                        </th>

                    </tr>

                    <tr class='encabezado-columnas'>

                        <th class='centrado'>Item</th>

                        <th class='centrado'>Asiento</th>

                        <th class='centrado'>Fecha</th>

                        <th class='centrado'>Factura</th>

                        <th class='centrado'>Recibo</th>

                        <th class='derecha'>Importe Bs.</th>

                        <th class='derecha'>Importe $us</th>

                    </tr>

                </thead>

                <tbody>

                @php

                    $cuentaAnterior = null;
                    $subcuentaAnterior = null;

                    $subtotalSubcuentaBs = 0;
                    $subtotalSubcuentaSus = 0;

                    $totalCuentaBs = 0;
                    $totalCuentaSus = 0;

                    $nombreCuentaAnterior = '';
                    $nombreSubcuentaAnterior = '';

                @endphp

                @foreach($asientos as $asi)

                    @php

                        $cuenta = \App\Models\Cuenta::find($asi->cuenta);

                        $subcuenta = \App\Models\SubCuenta::find($asi->sub_cuenta);

                    @endphp

                    {{-- CAMBIO DE CUENTA --}}
                    @if($cuentaAnterior != $asi->cuenta)

                        @if($subcuentaAnterior != null)

                            <tr class='fila-subtotal'>

                                <td colspan="5" class="derecha">

                                    SUBTOTAL {{ $nombreSubcuentaAnterior }}

                                </td>

                                <td class='derecha'>

                                    {{ number_format($subtotalSubcuentaBs,2) }}

                                </td>

                                <td class='derecha'>

                                    {{ number_format($subtotalSubcuentaSus,2) }}

                                </td>

                            </tr>

                        @endif

                        @if($cuentaAnterior != null)

                            <tr class='fila-total'>

                                <td colspan="5" class="derecha">

                                    TOTAL {{ $nombreCuentaAnterior }}

                                </td>

                                <td class='derecha'>

                                    {{ number_format($totalCuentaBs,2) }}

                                </td>

                                <td class='derecha'>

                                    {{ number_format($totalCuentaSus,2) }}

                                </td>

                            </tr>

                        @endif

                        <tr class='fila-cuenta'>

                            <td colspan="7">

                                {{ $cuenta->nombre }}

                            </td>

                        </tr>

                        @php

                            $totalCuentaBs = 0;
                            $totalCuentaSus = 0;

                            $subcuentaAnterior = null;

                        @endphp

                    @endif

                    {{-- CAMBIO DE SUBCUENTA --}}
                    @if($subcuentaAnterior != $asi->sub_cuenta)

                        @if($subcuentaAnterior != null)

                            <tr class='fila-subtotal'>

                                <td colspan="5" class="derecha">

                                    SUBTOTAL {{ $nombreSubcuentaAnterior }}

                                </td>

                                <td class='derecha'>

                                    {{ number_format($subtotalSubcuentaBs,2) }}

                                </td>

                                <td class='derecha'>

                                    {{ number_format($subtotalSubcuentaSus,2) }}

                                </td>

                            </tr>

                        @endif

                        <tr class='fila-subcuenta'>

                            <td colspan="7">

                                {{ $subcuenta->nombre }}

                            </td>

                        </tr>

                        @php

                            $subtotalSubcuentaBs = 0;
                            $subtotalSubcuentaSus = 0;

                        @endphp

                    @endif

                    {{-- MOVIMIENTO --}}
                    <tr>

                        <td class='centrado'>

                            {{ $loop->iteration }}

                        </td>

                        <td class='centrado'>

                            {{ $asi->id }}

                        </td>

                        <td class='centrado'>

                            {{ \Carbon\Carbon::parse($asi->fec_asiento)->format('d-m-Y') }}

                        </td>

                        <td class='centrado'>

                            {{ $asi->factura }}

                        </td>

                        <td class='centrado'>

                            {{ $asi->recibo }}

                        </td>

                        <td class='derecha'>

                            {{ number_format($asi->monto_bs,2) }}

                        </td>

                        <td class='derecha'>

                            {{ number_format($asi->monto_sus,2) }}

                        </td>

                    </tr>

                  @php $subtotalSubcuentaBs += $asi->monto_bs; 
                       $subtotalSubcuentaSus += $asi->monto_sus;
                       $totalCuentaBs += $asi->monto_bs;
                       $totalCuentaSus += $asi->monto_sus; // TOTAL GENERAL
                       $sumatotalbs += $asi->monto_bs; 
                       $sumatotalsus += $asi->monto_sus;
                       $cuentaAnterior = $asi->cuenta;
                       $subcuentaAnterior = $asi->sub_cuenta; 
                       $nombreCuentaAnterior = $cuenta->nombre;
                       $nombreSubcuentaAnterior = $subcuenta->nombre;
                  @endphp

                @endforeach

                {{-- cierre final subcuenta --}}
                <tr class='fila-subtotal'>

                    <td colspan="5" class="derecha">

                        SUBTOTAL {{ $nombreSubcuentaAnterior }}

                    </td>

                    <td class='derecha'>

                        {{ number_format($subtotalSubcuentaBs,2) }}

                    </td>

                    <td class='derecha'>

                        {{ number_format($subtotalSubcuentaSus,2) }}

                    </td>

                </tr>

                {{-- cierre final cuenta --}}
                <tr class='fila-total'>

                    <td colspan="5" class="derecha">

                        TOTAL {{ $nombreCuentaAnterior }}

                    </td>

                    <td class='derecha'>

                        {{ number_format($totalCuentaBs,2) }}

                    </td>

                    <td class='derecha'>

                        {{ number_format($totalCuentaSus,2) }}

                    </td>

                </tr>

                {{-- TOTAL GENERAL --}}
                <tr class='fila-total-general'>

                    <td colspan="5" class='derecha'>

                        TOTAL GENERAL

                    </td>

                    <td class='derecha'>

                        {{ number_format($sumatotalbs, 2) }}

                    </td>

                    <td class='derecha'>

                        {{ number_format($sumatotalsus, 2) }}

                    </td>

                </tr>

                </tbody>

            </table>

        </div>

    </div>

    {{-- SEGUNDA TABLA --}}

  
    <div id='secundario'>

        <table class='table'>

            <thead>

                <tr class='encabezado-principal'>

                    <td colspan="4" class='centrado'>

                        HOJA DE PRESENTACIÓN DE ESTADO DE RESULTADOS

                    </td>

                </tr>

                <tr class='encabezado-principal'>

                    <td colspan="4" class='centrado'>

                        FUNDACIÓN ARCA DE RESCATE DE LOS NIÑOS

                    </td>

                </tr>

                <tr class='fila-cuenta'>

                    <td colspan="4" class='centrado'>

                        {{ $nombremes }} {{ $anio }}

                    </td>

                </tr>

                <tr class='encabezado-columnas'>

                    <td colspan="4" class='centrado'>

                        Del {{ \Carbon\Carbon::parse($fecinicio)->format('d-m-Y') }}

                        al {{ \Carbon\Carbon::parse($fecfin)->format('d-m-Y') }}

                    </td>

                </tr>

            </thead>

@php
 $ingresosMes = $detallecreditos->sum('credito');
 $saldoInicial = $sumacreditos;
 @endphp
            <tbody>

                {{-- TOTAL INGRESOS --}}
                <tr class='fila-total-general'>

                    <td class='!text-center'><strong>TOTAL INGRESOS</strong></td>

                    <td></td>
                    <td></td>

                    <td class='derecha'>

                        <strong>

                            {{ number_format($sumacreditos + $interescredito + $saldo, 2) }}

                        </strong>

                    </td>

                </tr>

                {{-- INGRESOS --}}
                <tr class='fila-total'>
                    <td></td>

                    <td  style='text-align:center !important'><strong  class='!text-center'>INGRESOS DEL MES  {{ $nombremes }} {{ $anio }} </strong</td>
                    <td style='text-align:right !important'> {{ number_format($sumacreditos, 2) }}</td>
                    <td></td>

                </tr>

               >

                {{-- SALDO INICIAL --}}
                <tr>
                     <td></td>
                    <td>

                        Saldo inicial

                    </td>
                     <td class='derecha'>

                        {{ number_format($saldo, 2) }}

                    </td>  
                   
                     <td></td>

                   

                </tr>

                {{-- DEPOSITOS --}}
                @foreach($detallecreditos as $detalle)

                    <tr>
                         <td></td>
                        <td>

                            Depósito
                            {{ \Carbon\Carbon::parse($detalle->fecha)->format('d-m-Y') }}

                        </td>

                         <td class='derecha'>

                            {{ number_format($detalle->credito, 2) }}

                        </td>                       

                        <td></td>

                       

                    </tr>

                @endforeach

                {{-- OTROS INGRESOS --}}
                <tr>
                     <td></td>

                    <td style='text-align:center !important;color:black !important' >

                       <strong> OTROS INGRESOS</strong>

                    </td>
                    
                     <td></td>

                   
                </tr>

                {{-- INTERES GANADO --}}
                <tr>
                     <td></td>
                    <td>

                        Interés ganado

                    </td>

                     <td class='derecha'>

                        {{ number_format($interescredito, 2) }}

                    </td>
                    
                    <td></td>

                   
                </tr>

                {{-- TOTAL EGRESOS --}}
                <tr class='fila-total-general'>

                    <td><strong>TOTAL EGRESOS</strong></td>

                    <td></td>
                    <td></td>

                    <td class='derecha'>

                        <strong>

                            {{ number_format($gastosoperativos, 2) }}

                        </strong>

                    </td>

                </tr>

                {{-- EGRESOS --}}
                <tr class='fila-total'>
                      <td></td>   
                    <td style='text-align:center !important'><strong>GASTOS OPERATIVOS  {{ $nombremes }} {{ $anio }} </strong></td>
                    <td></td>
                    <td></td>

                </tr>

                {{-- DETALLE EGRESOS --}}
                @foreach($resultados as $fila)

                    <tr>
                         <td></td>   
                        <td>

                            {{ $fila->cuenta_nombre }}

                        </td>
                          <td class='derecha'>

                            {{ number_format($fila->total, 2) }}

                        </td>
                       
                        <td></td>

                      

                    </tr>

                @endforeach

                {{-- SALDO FINAL --}}
                <tr class='fila-total-general'>
                     
                    <td colspan='2' style='text-align:center !important'>

                        <strong>SALDO INGRESOS Y GASTOS</strong>

                    </td>

                    <td></td>

                    <td class='derecha'>

                        <strong>

                            {{ number_format($sumacreditos + $interescredito + $saldo-$gastosoperativos, 2) }}

                        </strong>

                    </td>

                </tr>

                {{-- VARIACION --}}
                <tr>
                     
                    <td colspan='2' style='text-align:center !important'>

                        <strong>VARIACIÓN</strong>

                    </td>

                   
                    <td></td>
                     <td class='derecha'> {{ number_format($sumacreditos + $interescredito + $saldo-$gastosoperativos-$saldofinal, 2) }} </td>    
                 

                </tr>

                {{-- SALDO BANCARIO --}}
                <tr class='fila-total'>
                         
                    <td colspan='2' style='text-align:center !important'>

                        <strong>SALDO SEGUN EXTRACTO BANCARIO</strong>

                    </td>

                   
                    <td></td>

                    <td class='derecha'>

                        <strong>

                            {{ number_format($saldofinal, 2) }}

                        </strong>

                    </td>

                </tr>

            </tbody>

        </table>

    </div>

</div>

@endsection


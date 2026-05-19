<table>

    <colgroup>
        <col width="10">
        <col width="15">
        <col width="15">
        <col width="20">
        <col width="20">
        <col width="20">
        <col width="20">
    </colgroup>

    <thead>

        <tr>

            <th colspan="7"
                style="
                    background:#1f4e78;
                    color:white;
                    font-weight:bold;
                    text-align:center;
                    border:1px solid black;
                    font-size:14px;
                ">

                REPORTE GENERAL DE GASTOS

                {{ \Carbon\Carbon::parse($fecinicio)->format('d-m-Y') }}

                -

                {{ \Carbon\Carbon::parse($fecfin)->format('d-m-Y') }}

            </th>

        </tr>

        <tr>

            <th style="background:#4472c4;color:white;border:1px solid black;text-align:center;font-weight:bold;">
                Item
            </th>

            <th style="background:#4472c4;color:white;border:1px solid black;text-align:center;font-weight:bold;">
                Asiento
            </th>

            <th style="background:#4472c4;color:white;border:1px solid black;text-align:center;font-weight:bold;">
                Fecha
            </th>

            <th style="background:#4472c4;color:white;border:1px solid black;text-align:center;font-weight:bold;">
                Factura
            </th>

            <th style="background:#4472c4;color:white;border:1px solid black;text-align:center;font-weight:bold;">
                Recibo
            </th>

            <th style="background:#4472c4;color:white;border:1px solid black;text-align:center;font-weight:bold;">
                Importe Bs.
            </th>

           

        </tr>

    </thead>

    <tbody>

@php

$cuentaAnterior = null;
$subcuentaAnterior = null;

$subtotalSubcuentaBs = 0;


$totalCuentaBs = 0;


$nombreCuentaAnterior = '';
$nombreSubcuentaAnterior = '';

@endphp

@foreach($asientos as $asi)

@php

$cuenta = \App\Models\Cuenta::find($asi->cuenta);
$subcuenta = \App\Models\SubCuenta::find($asi->sub_cuenta);

@endphp

@if($cuentaAnterior != $asi->cuenta)

    @if($subcuentaAnterior != null)

        <tr>

            <td colspan="5"
                style="
                    background:#fff2cc;
                    border:1px solid black;
                    text-align:right;
                    font-weight:bold;
                ">

                SUBTOTAL {{ $nombreSubcuentaAnterior }}

            </td>

            <td style="background:#fff2cc;border:1px solid black;text-align:right;font-weight:bold;">

                {{ number_format($subtotalSubcuentaBs,2) }}

            </td>

           

        </tr>

    @endif

    @if($cuentaAnterior != null)

        <tr>

            <td colspan="5"
                style="
                    background:#c6e0b4;
                    border:1px solid black;
                    text-align:right;
                    font-weight:bold;
                ">

                TOTAL {{ $nombreCuentaAnterior }}

            </td>

            <td style="background:#c6e0b4;border:1px solid black;text-align:right;font-weight:bold;">

                {{ number_format($totalCuentaBs,2) }}

            </td>

           

        </tr>

    @endif

    <tr>

        <td colspan="7"
            style="
                background:#d9e2f3;
                border:1px solid black;
                text-align:center;
                font-weight:bold;
            ">

            {{ $cuenta->nombre }}

        </td>

    </tr>

    @php

        $totalCuentaBs = 0;
       

        $subcuentaAnterior = null;

    @endphp

@endif

@if($subcuentaAnterior != $asi->sub_cuenta)

    @if($subcuentaAnterior != null)

        <tr>

            <td colspan="5"
                style="
                    background:#fff2cc;
                    border:1px solid black;
                    text-align:right;
                    font-weight:bold;
                ">

                SUBTOTAL {{ $nombreSubcuentaAnterior }}

            </td>

            <td style="background:#fff2cc;border:1px solid black;text-align:right;font-weight:bold;">

                {{ number_format($subtotalSubcuentaBs,2) }}

            </td>

          

        </tr>

    @endif

    <tr>

        <td colspan="7"
            style="
                background:#edf2f9;
                border:1px solid black;
                text-align:center;
                font-weight:bold;
            ">

            {{ $subcuenta->nombre }}

        </td>

    </tr>

    @php

        $subtotalSubcuentaBs = 0;
       

    @endphp

@endif

<tr>

    <td style="border:1px solid black;text-align:center;">

        {{ $loop->iteration }}

    </td>

    <td style="border:1px solid black;text-align:center;">

        {{ $asi->id }}

    </td>

    <td style="border:1px solid black;text-align:center;">

        {{ \Carbon\Carbon::parse($asi->fec_asiento)->format('d-m-Y') }}

    </td>

    <td style="border:1px solid black;text-align:center;">

        {{ $asi->factura }}

    </td>

    <td style="border:1px solid black;text-align:center;">

        {{ $asi->recibo }}

    </td>

    <td style="border:1px solid black;text-align:right;">

        {{ number_format($asi->monto_bs,2) }}

    </td>

   

</tr>

@php

$subtotalSubcuentaBs += $asi->monto_bs;


$totalCuentaBs += $asi->monto_bs;


$sumatotalbs += $asi->monto_bs;


$cuentaAnterior = $asi->cuenta;
$subcuentaAnterior = $asi->sub_cuenta;

$nombreCuentaAnterior = $cuenta->nombre;
$nombreSubcuentaAnterior = $subcuenta->nombre;

@endphp

@endforeach

<tr>

    <td colspan="5"
        style="
            background:#fff2cc;
            border:1px solid black;
            text-align:right;
            font-weight:bold;
        ">

        SUBTOTAL {{ $nombreSubcuentaAnterior }}

    </td>

    <td style="background:#fff2cc;border:1px solid black;text-align:right;font-weight:bold;">

        {{ number_format($subtotalSubcuentaBs,2) }}

    </td>

   
</tr>

<tr>

    <td colspan="5"
        style="
            background:#c6e0b4;
            border:1px solid black;
            text-align:right;
            font-weight:bold;
        ">

        TOTAL {{ $nombreCuentaAnterior }}

    </td>

    <td style="background:#c6e0b4;border:1px solid black;text-align:right;font-weight:bold;">

        {{ number_format($totalCuentaBs,2) }}

    </td>

   
</tr>

<tr>

    <td colspan="5"
        style="
            background:#70ad47;
            color:white;
            border:1px solid black;
            text-align:right;
            font-weight:bold;
        ">

        TOTAL GENERAL

    </td>

    <td style="background:#70ad47;color:white;border:1px solid black;text-align:right;font-weight:bold;">

        {{ number_format($sumatotalbs,2) }}

    </td>

   
</tr>

</tbody>

</table>

<br><br><br>

<table>

   <colgroup>

    {{-- PRIMERA COLUMNA MÁS ANCHA --}}
    <col width="30">

    {{-- SEGUNDA COLUMNA --}}
    <col width="45">

    {{-- TERCERA COLUMNA MÁS ANCHA --}}
    <col width="35">

    {{-- CUARTA COLUMNA --}}
    <col width="20">

</colgroup>

    <thead>

        <tr>

            <th colspan="4"
                style="
                    background:#1f4e78;
                    color:white;
                    text-align:center;
                    font-weight:bold;
                    border:1px solid black;
                ">

                HOJA DE PRESENTACIÓN DE ESTADO DE RESULTADOS

            </th>

        </tr>

        <tr>

            <th colspan="4"
                style="
                    background:#1f4e78;
                    color:white;
                    text-align:center;
                    font-weight:bold;
                    border:1px solid black;
                ">

                FUNDACIÓN ARCA DE RESCATE DE LOS NIÑOS

            </th>

        </tr>

        <tr>

            <th colspan="4"
                style="
                    background:#d9e2f3;
                    border:1px solid black;
                    text-align:center;
                    font-weight:bold;
                ">

                {{ $nombremes }} {{ $anio }}

            </th>

        </tr>

        <tr>

            <th colspan="4"
                style="
                    background:#4472c4;
                    color:white;
                    border:1px solid black;
                    text-align:center;
                    font-weight:bold;
                ">

                Del {{ \Carbon\Carbon::parse($fecinicio)->format('d-m-Y') }}

                al {{ \Carbon\Carbon::parse($fecfin)->format('d-m-Y') }}

            </th>

        </tr>

        <tr>

            <th colspan="4"
                style="
                    background:#4472c4;
                    color:white;
                    border:1px solid black;
                    text-align:center;
                    font-weight:bold;
                ">

                (Expresado en Bolivianos)

            </th>

        </tr>

    </thead>

    <tbody>

@php
 $ingresosMes = $detallecreditos->sum('credito');
 $saldoInicial = $sumacreditos;
@endphp

<tr>

    <td style="
        background:#70ad47;
        color:white;
        border:1px solid black;
        font-weight:bold;
        text-align:center;
    ">

        TOTAL INGRESOS

    </td>

    <td style="border:1px solid black;"></td>

    <td style="border:1px solid black;"></td>

    <td style="
        background:#70ad47;
        color:white;
        border:1px solid black;
        text-align:right;
        font-weight:bold;
    ">

        {{ number_format($sumacreditos + $interescredito + $saldo, 2) }}

    </td>

</tr>

<tr>

    <td style="border:1px solid black;"></td>

    <td style="
        background:#c6e0b4;
        border:1px solid black;
        text-align:center;
        font-weight:bold;
    ">

        INGRESOS DEL MES {{ $nombremes }} {{ $anio }}

    </td>

    <td style="
        border:1px solid black;
        text-align:right;
        font-weight:bold;
    ">

        {{ number_format($sumacreditos, 2) }}

    </td>

    <td style="border:1px solid black;"></td>

</tr>

<tr>

    <td style="border:1px solid black;"></td>

    <td style="border:1px solid black;">

        Saldo inicial

    </td>

    <td style="
        border:1px solid black;
        text-align:right;
    ">

     @php
                $saldoaux=$sumacreditos-$sumadebitos;
                $saldoinicial=$saldofinal-$saldoaux;
     @endphp

        {{ number_format($saldoinicial, 2) }}

    </td>

    <td style="border:1px solid black;"></td>

</tr>

@foreach($detallecreditos as $detalle)

<tr>

    <td style="border:1px solid black;"></td>

    <td style="border:1px solid black;">

        Depósito
        {{ \Carbon\Carbon::parse($detalle->fecha)->format('d-m-Y') }}

    </td>

    <td style="
        border:1px solid black;
        text-align:right;
    ">

        {{ number_format($detalle->credito, 2) }}

    </td>

    <td style="border:1px solid black;"></td>

</tr>

@endforeach

<tr>

    <td style="border:1px solid black;"></td>

    <td style="
        background:#d9e2f3;
        border:1px solid black;
        text-align:center;
        font-weight:bold;
    ">

        OTROS INGRESOS

    </td>

    <td style="border:1px solid black;"></td>

    <td style="border:1px solid black;"></td>

</tr>

<tr>

    <td style="border:1px solid black;"></td>

    <td style="border:1px solid black;">

        Interés ganado

    </td>

    <td style="
        border:1px solid black;
        text-align:right;
    ">

        {{ number_format($interescredito, 2) }}

    </td>

    <td style="border:1px solid black;"></td>

</tr>

<tr>

    <td style="
        background:#70ad47;
        color:white;
        border:1px solid black;
        text-align:center;
        font-weight:bold;
    ">

        TOTAL EGRESOS

    </td>

    <td style="border:1px solid black;"></td>

    <td style="border:1px solid black;"></td>

    <td style="
        background:#70ad47;
        color:white;
        border:1px solid black;
        text-align:right;
        font-weight:bold;
    ">

        {{ number_format($gastosoperativos, 2) }}

    </td>

</tr>

@foreach($resultados as $fila)

<tr>

    <td style="border:1px solid black;"></td>

    <td style="border:1px solid black;">

        {{ $fila->cuenta_nombre }}

    </td>

    <td style="
        border:1px solid black;
        text-align:right;
    ">

        {{ number_format($fila->total, 2) }}

    </td>

    <td style="border:1px solid black;"></td>

</tr>

@endforeach

<tr>

    <td colspan="2"
        style="
            background:#70ad47;
            color:white;
            border:1px solid black;
            text-align:center;
            font-weight:bold;
        ">

        SALDO INGRESOS Y GASTOS

    </td>

    <td style="border:1px solid black;"></td>

    <td style="
        background:#70ad47;
        color:white;
        border:1px solid black;
        text-align:right;
        font-weight:bold;
    ">

        {{ number_format($sumacreditos + $interescredito + $saldo-$gastosoperativos, 2) }}

    </td>

</tr>

<tr>

    <td colspan="2"
        style="
            border:1px solid black;
            text-align:center;
            font-weight:bold;
        ">

        VARIACIÓN

    </td>

    <td style="border:1px solid black;"></td>
    <td style="border:1px solid black;text-align:right"> {{ number_format($sumacreditos + $interescredito + $saldo-$gastosoperativos-$saldofinal, 2) }} </td>


</tr>

<tr>

    <td colspan="2"
        style="
            background:#c6e0b4;
            border:1px solid black;
            text-align:center;
            font-weight:bold;
        ">

        SALDO SEGUN EXTRACTO BANCARIO

    </td>

    <td style="border:1px solid black;"></td>

    <td style="
        background:#c6e0b4;
        border:1px solid black;
        text-align:right;
        font-weight:bold;
    ">

        {{ number_format($saldofinal, 2) }}

    </td>

</tr>

<tr>
    <td colspan="4"
        style="
            height:120px;
            border:none;
        ">
    </td>
</tr>

<tr>

    <td colspan="2"
        style="
            text-align:center;
            border:none;
            font-weight:bold;
            width:50%;
        ">

        ___________________________________

        <br><br>

        Lic. Aud. Silvia Aguilar García

        <br>

        CAUB  19836
       
       <br>

       CDA-16-MM85

    </td>

    <td colspan="2"
        style="
            text-align:center;
            border:none;
            font-weight:bold;
            width:50%;
        ">

        ___________________________________

        <br><br>

        Ing. Javier Fidel Guillén Escalera

        <br>

        C.I. 997213 Cbba.

        <br>

        REPRESENTANTE LEGAL

    </td>

</tr>

</tbody>

</table>

@extends('layouts.app')

@section('content')

<div class="container">
    <h3 class="mb-3 text-center">Resumen Caja Chica</h3>

    {{-- FILTROS --}}
    <form method="GET" class="mb-4">
        <div class="row align-items-end">

            <div class="col-md-5 mb-2">
                <label>Gestión</label>
                <select name="gestion_id" class="form-control" required>
                    <option value="">Seleccione gestión</option>
                    @foreach($gestiones as $g)
                        <option value="{{ $g->id }}" 
                            {{ $gestionId == $g->id ? 'selected' : '' }}>
                            {{ $g->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-5 mb-2">
                <label>Mes</label>
                <select name="mes" class="form-control" required>
                    <option value="">Seleccione mes</option>
                    @php
                        $meses = [
                            1=>'Enero',2=>'Febrero',3=>'Marzo',4=>'Abril',
                            5=>'Mayo',6=>'Junio',7=>'Julio',8=>'Agosto',
                            9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre'
                        ];
                    @endphp

                    @foreach($meses as $num => $nombre)
                        <option value="{{ $num }}" 
                            {{ $mes == $num ? 'selected' : '' }}>
                            {{ $nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- BOTONES --}}
            <div class="col-md-2 mb-2 text-right">
                <button type="button" onclick="imprimirTabla()" class="btn btn-secondary btn-sm w-100 mb-1">
                    Imprimir
                </button>
                <button class="btn btn-primary btn-sm mb-1 w-100">Buscar</button>
               
            </div>

        </div>
    </form>

    {{-- TABLA --}}
    @if($gestionId && $mes)
    <div class="table-responsive">
        <table id="tablaCaja" class="table table-bordered table-sm">
            <thead class="thead-dark">
                <tr>
                    <th class="text-center">Ingresos Bs.</th>
                    <th class="text-center">Egresos Bs.</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $max = max($ingresos->count(), $egresos->count());
                @endphp

                @for ($i = 0; $i < $max; $i++)
                    <tr>
                        {{-- INGRESOS --}}
                        <td>
                            @if(isset($ingresos[$i]))
                                <div class="d-flex justify-content-end">
                                   
                                    <strong>
                                         {{ number_format($ingresos[$i]->monto, 2) }}
                                    </strong>
                                </div>
                            @endif
                        </td>

                        {{-- EGRESOS --}}
                        <td>
                            @if(isset($egresos[$i]))
                                <div class="d-flex justify-content-end">
                                   
                                    <strong>
                                         {{ number_format($egresos[$i]->monto, 2) }}
                                    </strong>
                                </div>
                            @endif
                        </td>
                    </tr>
                @endfor

                {{-- TOTALES --}}
                <tr class="font-weight-bold bg-light">
                    <td class="text-center">
                        Total: Bs {{ number_format($totalIngresos, 2) }}
                    </td>
                    <td class="text-center">
                        Total: Bs {{ number_format($totalEgresos, 2) }}
                    </td>
                </tr>

                {{-- RESULTADO FINAL --}}
                <tr>
                    <td colspan="2" 
                        class="text-center text-white font-weight-bold py-3
                        @if($diferencia > 0) bg-secondary
                        @elseif($diferencia < 0) bg-danger
                        @else bg-success
                        @endif">

                        <div>
                            Diferencia: Bs {{ number_format($diferencia, 2) }}
                        </div>
                        <div>
                            {{ $mensaje }}
                        </div>

                    </td>
                </tr>

            </tbody>
        </table>
    </div>
    @endif

</div>

<style>
.table td span {
    max-width: 140px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* Responsive */
@media (max-width: 576px) {
    h3 {
        font-size: 18px;
        text-align: center;
    }

    .table {
        font-size: 12px;
    }

    .table td {
        padding: 6px;
    }

    .table strong {
        font-size: 12px;
    }
}
</style>

{{-- SCRIPT IMPRIMIR --}}
<script>
function imprimirTabla() {
    var contenido = document.getElementById('tablaCaja').outerHTML;
    var ventana = window.open('', '', 'height=700,width=900');

    ventana.document.write(`
        <html>
        <head>
            <title>Resumen Caja Chica</title>
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
            
            <style>
                body {
                    padding: 20px;
                    font-family: Arial, sans-serif;
                }

                h3 {
                    text-align: center;
                    margin-bottom: 20px;
                }

                table {
                    width: 100%;
                    border-collapse: collapse;
                }

                table, th, td {
                    border: 1px solid #000 !important;
                }

                th {
                    background: #343a40 !important;
                    color: #fff !important;
                    text-align: center;
                }

                td {
                    padding: 6px;
                }

                .total {
                    font-weight: bold;
                }

                .resultado {
                    font-weight: bold;
                    text-align: center;
                    padding: 10px;
                }
            </style>
        </head>
        <body>

            <h3>Resumen Caja Chica</h3>

            ${contenido}

        </body>
        </html>
    `);

    ventana.document.close();
    ventana.focus();

    // Espera a que cargue estilos antes de imprimir
    setTimeout(() => {
        ventana.print();
        ventana.close();
    }, 500);
}
</script>
@endsection

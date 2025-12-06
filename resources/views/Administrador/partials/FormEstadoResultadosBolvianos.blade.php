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

        /* ---------------- Responsive ---------------- */
        @media (max-width: 1024px) {
            .contenedor {
                width: 100%;
                margin-top: 20px;
            }

            .caja,
            .espaciador {
                display: block;
                width: 100%;
                margin: 0 auto 20px auto;
            }
        }

        @media (max-width: 768px) {
            table {
                display: block;
                width: 100%;
                overflow-x: auto;
                border-collapse: collapse;
            }

            thead {
                display: none;
            }

            tbody tr {
                display: block;
                margin-bottom: 1rem;
                border: 1px solid #ccc;
                padding: 0.5rem;
                border-radius: 0.25rem;
            }

            tbody td {
                display: flex;
                justify-content: space-between;
                padding: 0.25rem 0.5rem;
                border: none;
                border-bottom: 1px solid #eee;
            }

            tbody td:last-child {
                flex-direction: column;
                gap: 0.25rem;
            }

            tbody td::before {
                content: attr(data-label);
                font-weight: bold;
                flex: 1;
            }

            .btn {
                width: 100%;
                text-align: center;
            }

            form.d-inline {
                width: 100%;
            }

            .contenedor {
                margin-top: 20px;
            }
        }

        @media (max-width: 480px) {
            .contenido {
                font-size: 14px;
                padding: 5px;
            }

            td {
                font-size: 12px;
            }
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

                <table class="table table-striped mb-2 pagination mx-auto mt-0">

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
                                <th colspan="1" style='width:32px;text-align:center;background-color:crimson'>Item #</th>
                                <th style='width:32px;text-align:center;background-color:crimson'>Num. Asiento</th>
                                <th style='width:96px;text-align:center;background-color:crimson'>Fecha</th>
                                <th style='width:96px;text-align:center;background-color:crimson'>Factura</th>
                                <th style='width:96px;text-align:center;background-color:crimson'>Recibo</th>
                                <th style='width:192px;text-align:center;background-color:crimson'>Importe Bs.</th>
                            </tr>
                        </thead>

                        @foreach ($asientos as $asi)
                            <!-- Aquí va tu código dinámico de filas como ya lo tienes -->
                            <tr>
                                <td data-label="Item #">{{ $cont = $cont + 1 }}</td>
                                <td data-label="Num. Asiento">{{ $asi->id }}</td>
                                <td data-label="Fecha">{{ \Carbon\Carbon::parse($asi->fec_asiento)->format('d-m-Y') }}</td>
                                <td data-label="Factura">{{ $asi->factura }}</td>
                                <td data-label="Recibo">{{ $asi->recibo }}</td>
                                <td data-label="Importe Bs.">{{ $asi->monto_bs }}</td>
                            </tr>
                        @endforeach

                        <tr>
                            <td colspan="3" style='font-weight:bold;text-align:right;background-color:#458ab3'></td>
                            <td colspan="2"
                                style='font-weight:bold;text-align:right;background-color:#458ab3'>TOTAL GASTOS EN EL PERÍODO
                            </td>
                            <td colspan="1"
                                style='font-weight:bold;text-align:right;background-color:#458ab3'>
                                {{ number_format($sumatotalbs, 2) }}</td>
                        </tr>
                    </tbody>
                </table>

            </div><!--- fin div card-body---->

        </div><!---fin class card---->

    </div><!---fin div principal---->

    <div id='secundario' style='width:720px; height:820px;margin-top:20px'>
        <table style='width:720px;border:none'>
            <thead class='border-none'>
                <tr>
                    <td colspan="3" style='font-weight:bold;text-align:center;border:none'>
                        HOJA DE PRESENTACIÓN DE ESTADO DE RESULTADOS
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style='font-weight:bold;text-align:center;border:none'>
                        FUNDACIÓN ARCA DE RESCATE DE LOS NIÑOS
                    </td>
                </tr>
                <tr>
                    <td colspan="3"
                        style='font-weight:bold;text-align:left;border:none;text-decoration:underline'>
                        {{ $nombremes }} &nbsp;{{ $anio }}
                    </td>
                </tr>
            </thead>
            <tbody>
                <!-- Aquí va el resto de tu tabla secundaria -->
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

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
        }

        .text-right {
            text-align: right;
        }

        .no-border td {
            border: none !important;
        }

        .firma {
            text-align: center;
            vertical-align: bottom;
        }

        .logo {
            width: 80px;
            height: auto;
        }

        .firma-img {
            width: 120px;
            height: auto;
        }

        .header-table td {
            border: none !important;
            vertical-align: middle;
        }

        .titulo {
            font-size: 20px;
            font-weight: bold;
            text-align: center;
        }

        .firma-space {
            height: 120px;
        }
    </style>
</head>

<body>

    @php
        /*
        |--------------------------------------------------------------------------
        | FIRMA BASE64
        |--------------------------------------------------------------------------
        */
        $firmaPath = storage_path('app/private/fotos/signature.png');

        $firmaBase64 = '';

        if (file_exists($firmaPath)) {
            $type = pathinfo($firmaPath, PATHINFO_EXTENSION);
            $data = file_get_contents($firmaPath);

            $firmaBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }
    @endphp

    <!-- CABECERA -->
    <table class="header-table">
        <tr>

            <!-- LOGO -->
            <td width="20%">
                <img
                    src="{{ public_path('imagenes/Logo.png') }}"
                    class="logo">
            </td>

            <!-- TITULO -->
            <td width="80%" class="titulo">
                Solicitud Caja Chica
            </td>

        </tr>
    </table>

    <br>

    <!-- DATOS -->
    <p>
        <strong>Código:</strong>
        {{ $solicitud->codigo }}
    </p>

    <p>
        <strong>Fecha:</strong>
        {{ $fecha_formateada }}
    </p>

    <p>
        <strong>Gestión:</strong>
        {{ $solicitud->gestion->nombre ?? '' }}
    </p>

    <!-- DETALLE -->
    <table>
        <thead>
            <tr>
                <th width="10%">#</th>
                <th width="70%">Descripción</th>
                <th width="20%">Cantidad</th>
            </tr>
        </thead>

        <tbody>
            @foreach($solicitud->detalles as $i => $detalle)
                <tr>
                    <td>
                        {{ $i + 1 }}
                    </td>

                    <td>
                        {{ $detalle->descripcion }}
                    </td>

                    <td class="text-right">
                        {{ number_format($detalle->cantidad, 2) }}
                    </td>
                </tr>
            @endforeach
        </tbody>

        <tfoot>
            <tr>

                <td colspan="2" class="text-right">
                    <strong>TOTAL</strong>
                </td>

                <td class="text-right">
                    <strong>
                        {{ number_format($total, 2) }}
                    </strong>
                </td>

            </tr>
        </tfoot>
    </table>

    <br><br><br>

    <!-- FIRMAS -->
    <table class="no-border" style="width:100%; margin-top:60px;">
        <tr>

            <!-- IZQUIERDA -->
            <td
                width="50%"
                class="firma">

                <div class="firma-space"></div>

                ___________________________
                <br>

                {{ $firmante->name ?? '' }}
                {{ $firmante->apellido ?? '' }}
                <br>

                Administrador
            </td>

            <!-- DERECHA -->
            <td
                width="50%"
                class="firma">

                @if($firmaBase64)
                    <img
                        src="{{ $firmaBase64 }}"
                        class="firma-img">
                @endif

                <br>

                ___________________________
                <br>

               JAVIER FIDEL GUILLÉN ESCALERA
                <br>

                Director
            </td>

        </tr>
    </table>

</body>

</html>
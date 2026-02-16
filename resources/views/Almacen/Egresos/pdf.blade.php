<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Comprobante de Egreso</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo {
            width: 120px;
        }

        .info {
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table, th, td {
            border: 1px solid black;
        }

        th {
            background-color: #f2f2f2;
        }

        th, td {
            padding: 6px;
            text-align: center;
        }

        td.descripcion {
            text-align: left;
        }

        .firmas {
            margin-top: 70px;
            width: 100%;
        }

        .firma-box {
            width: 45%;
            display: inline-block;
            text-align: center;
        }

        .linea {
            border-top: 1px solid black;
            margin-top: 60px;
        }
    </style>
</head>

<body>

    <div class="header">
        <img src="{{ $logo }}" width="120">
        <h2>COMPROBANTE DE EGRESO</h2>
    </div>

    <div class="info">
        <strong>Fecha:</strong> {{ \Carbon\Carbon::parse($egreso->fecha)->format('d/m/Y') }}
        <strong>Destinatario:</strong> {{ $egreso->destinatario->nombre }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th width="100">Cantidad</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($productosFormateados as $item)
                <tr>
                    <td class="descripcion">{{ $item['nombre'] }}</td>
                    <td>{{ $item['cantidad'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="firmas">
        <div class="firma-box">
            <div class="linea"></div>
            Entregado por
        </div>

        <div style="float:right; width:45%; text-align:center;">
            <div class="linea"></div>
            Recibido por
        </div>
    </div>

</body>
</html>
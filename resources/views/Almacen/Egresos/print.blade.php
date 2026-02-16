<!DOCTYPE html>
<html>
<head>
    <title>Comprobante de Egreso</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: center;
        }
        .firmas {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
        }
        .firma {
            width: 45%;
            text-align: center;
        }
        .linea {
            border-top: 1px solid black;
            margin-top: 50px;
        }
    </style>
</head>
<body onload="window.print()">

    <h2 style="text-align:center;">COMPROBANTE DE EGRESO</h2>

    <p><strong>Fecha:</strong> {{ $egreso->fecha }}</p>
    <p><strong>Destinatario:</strong> {{ $egreso->destinatario->nombre }}</p>

    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
            </tr>
        </thead>
        <tbody>
            @foreach($egreso->detalles as $detalle)
            <tr>
                <td>{{ $detalle->producto->nombre }}</td>
                <td>{{ $detalle->cantidad }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="firmas">
        <div class="firma">
            <div class="linea"></div>
            Entregado por
        </div>

        <div class="firma">
            <div class="linea"></div>
            Recibido por
        </div>
    </div>

</body>
</html>
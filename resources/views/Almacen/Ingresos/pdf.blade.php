<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Comprobante de Ingreso</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 30px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo {
            width: 120px;
            margin-bottom: 10px;
        }

        .info {
            margin-bottom: 15px;
        }

        .info p {
            margin: 3px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th {
            background: #f2f2f2;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }

        .total {
            text-align: right;
            margin-top: 15px;
            font-size: 14px;
        }

        .firma {
            margin-top: 60px;
            text-align: center;
        }

        .firma-linea {
            margin-top: 50px;
            border-top: 1px solid #000;
            width: 250px;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
</head>
<body>

    <div class="header">
        <img src="{{ $logo }}" class="logo">
        <h2>COMPROBANTE DE INGRESO</h2>
        <strong>N° {{ $ingreso->id }}</strong>
    </div>

    <div class="info">
        <p><strong>Fecha:</strong> {{ $ingreso->fecha }}</p>
        <p><strong>Origen del Fondo:</strong> {{ $ingreso->origen->nombre ?? '' }}</p>

        @if($ingreso->factura)
            <p><strong>Factura:</strong> {{ $ingreso->factura }}</p>
        @endif

        @if($ingreso->recibo)
            <p><strong>Recibo:</strong> {{ $ingreso->recibo }}</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp

            @foreach($productosFormateados as $producto)
                @php $total += $producto['subtotal']; @endphp
                <tr>
                    <td>{{ $producto['nombre'] }}</td>
                    <td>{{ number_format($producto['cantidad'], 2) }}</td>
                    <td>{{ number_format($producto['precio'], 2) }}</td>
                    <td>{{ number_format($producto['subtotal'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        <strong>TOTAL: {{ number_format($total, 2) }}</strong>
    </div>

    <div class="firma">
        <div class="firma-linea"></div>
          Responsable
    </div>

</body>
</html>
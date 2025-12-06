<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Residentes</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #444;
            padding: 5px;
            text-align: center;
        }
        th {
            background-color: #ddd;
        }
        h2 {
            text-align: center;
        }
    </style>
</head>
<body>
    <h2>Reporte de Residentes</h2>

    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Fecha de Nacimiento</th>
                <th>Edad</th>
                <th>CI</th>
                <th>Extensión</th>
                <th>Ciudad</th>
                <th>Provincia</th>
                <th>Fecha de Ingreso</th>
                <th>Estadía</th>
                <th>Tipología</th>
                <th>Ciudad Acogida</th>
                <th>Municipio Acogida</th>
                <th>Juzgado</th>
                <th>Documento</th>
            </tr>
        </thead>
        <tbody>
            @foreach($residentes as $r)
                <tr>
                    <td>{{ $r['nombre'] }}</td>
                    <td>{{ $r['apellido'] }}</td>
                    <td>{{ \Carbon\Carbon::parse($r['fecha_nacimiento'])->format('d/m/Y') }}</td>
                    <td>{{ $r['edad'] }}</td>
                    <td>{{ $r['ci'] }}</td>
                    <td>{{ $r['extension'] }}</td>
                    <td>{{ $r['ciudad'] }}</td>
                    <td>{{ $r['provincia'] }}</td>
                    <td>{{ \Carbon\Carbon::parse($r['fecha_ingreso'])->format('d/m/Y') }}</td>
                    <td>{{ $r['estadia'] }}</td>
                    <td>{{ $r['tipologia_acogida'] ?? '' }}</td>
                    <td>{{ $r['ciudad_acogida'] }}</td>
                    <td>{{ $r['municipio_acogida'] }}</td>
                    <td>{{ $r['juzgado'] }}</td>
                    <td>{{ $r['documento'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

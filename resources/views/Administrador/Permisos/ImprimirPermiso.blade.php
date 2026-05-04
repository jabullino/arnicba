<!DOCTYPE html>

<html>
<head>
    <meta charset="UTF-8">
<style>
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 14px;
        margin: 20px;
    }

.container {
    width: 100%;
}

.header-table {
    width: 100%;
    margin-bottom: 20px;
}

.logo {
    width: 120px;
}

.titulo {
    text-align: center;
    font-weight: bold;
    font-size: 18px;
}

.campo {
    margin-bottom: 8px;
}

.tabla-fechas {
    width: 100%;
    margin-bottom: 10px;
}

.tabla-fechas td {
    width: 33%;
}

.firmas {
    width: 100%;
    margin-top: 60px;
    text-align: center;
}

.firmas td {
    width: 33%;
    vertical-align: bottom;
}

.firma-img {
    height: 80px;
    display: block;
    margin: 0 auto 5px auto;
}

.linea {
    margin-top: 5px;
}

.sello-box {
    border: 1px dashed #000;
    height: 80px;
    margin: 0 auto 5px auto;
    text-align: center;
    font-size: 12px;
    padding-top: 25px;
}
```

</style>
</head>

<body>

<div class="container">

<!-- HEADER -->
<table class="header-table">
    <tr>
        <td style="width: 20%;">
            <img src="{{ public_path('imagenes/Logo.png') }}" class="logo">
        </td>
        <td class="titulo">
            PERMISO DE SALIDA
        </td>
        <td style="width: 20%;"></td>
    </tr>
</table>

<div class="campo"><strong>N°:</strong> {{ $permiso->num_permiso }}</div>
<div class="campo"><strong>Gestión:</strong> {{ $permiso->gestion_nombre }}</div>
<div class="campo"><strong>Solicitante:</strong> {{ $permiso->user_nombre }} {{ $permiso->user_apellido }}</div>

<!-- FECHAS -->
<table class="tabla-fechas">
    <tr>
        <td>
            <strong>Fecha salida:</strong><br>
            {{ \Carbon\Carbon::parse($permiso->fecha_salida)->format('d-m-Y') }}
        </td>
        <td>
            <strong>Hora salida:</strong><br>
            {{ $permiso->hora_salida }}
        </td>
        <td>
            <strong>Hora retorno:</strong><br>
            {{ $permiso->hora_retorno }}
        </td>
    </tr>
</table>

<div class="campo"><strong>Destino:</strong> {{ $permiso->destino }}</div>
<div class="campo"><strong>Motivo:</strong> {{ $permiso->motivo }}</div>

<!-- FIRMAS -->
<table class="firmas">
    <tr>
        <!-- solicitante -->
        <td>
            <div style="height:80px;"></div>
            ___________________<br>
            Firma solicitante
        </td>

        <!-- sello -->
        <td>
            <div class="sello-box">
                SELLO Y FIRMA<br>
                INSTITUCIÓN
            </div>
            ___________________<br>
            Sello institución
        </td>

        <!-- autorizado -->
        <td>
            @if($firmaBase64)
                <img src="{{ $firmaBase64 }}" class="firma-img">
            @else
                <div style="height:80px;"></div>
            @endif
            ___________________<br>
            Firma autorizado
        </td>
    </tr>
</table>
```

</div>

</body>
</html>

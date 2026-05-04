@extends('layouts.app')

@section('content')

<style>
    .form-print {
        width: 100%;
        max-width: 700px;
        margin: auto;
        border: 1px solid #444;
        padding: 12px;
        font-size: 13px;

        /* DARK MODE */
        background: #121212;
        color: #ffffff;
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }

    .header img {
        height: 55px;
        max-width: 100%;
    }

    .estado {
        text-align: right;
    }

    .titulo {
        text-align: center;
        font-weight: bold;
        margin: 10px 0;
        color: #ffffff;
    }

    .form-group {
        margin-bottom: 8px;
        width: 100%;
    }

    label {
        font-weight: bold;
        font-size: 12px;
        color: #ffffff;
    }

    input, textarea, select {
        width: 100%;
        border: 1px solid #666;
        padding: 5px;
        font-size: 13px;
        border-radius: 4px;

        background: #1e1e1e;
        color: #ffffff;
    }

    input::placeholder,
    textarea::placeholder {
        color: #bbb;
    }

    .row {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .col {
        flex: 1 1 100%;
    }

    @media (min-width: 600px) {
        .col {
            flex: 1;
        }
    }

    @media (min-width: 900px) {
        .col-2 {
            flex: 0 0 48%;
        }

        .col-3 {
            flex: 0 0 32%;
        }
    }

    .firmas {
        margin-top: 25px;
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 20px;
    }

    .firma {
        flex: 1 1 45%;
        text-align: center;
    }

    .linea {
        margin-top: 40px;
        border-top: 1px solid #aaa;
    }

    /* 🖨️ IMPRESIÓN (modo claro automático) */
    @media print {
        .form-print {
            background: #ffffff;
            color: #000000;
            border: 1px solid #000;
        }

        label {
            color: #000;
        }

        input, textarea, select {
            background: #fff;
            color: #000;
            border: 1px solid #000;
        }

        .linea {
            border-top: 1px solid #000;
        }
    }
</style>
<div class="form-print">

    <!-- HEADER -->
    <div class="header">
        <img src="{{ asset('imagenes/Logo.svg') }}">

        <div class="estado">
            <label>Estado</label>
            <select name="estado">
                <option value="pendiente">Pendiente</option>
                <option value="autorizado">Autorizado</option>
            </select>
        </div>
    </div>

    <div class="titulo">
        PERMISO DE SALIDA
    </div>

    <form method="POST" action="">
        @csrf

        <!-- FILA 1 -->
        <div class="row">

            <div class="col col-2 form-group">
                <label>Gestión</label>
                <select name="gestion" >
                    @foreach($gestiones as $gestion)
                        <option value="{{ $gestion->id }}" class='text-cente'>{{ $gestion->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col col-2 form-group">
                <label>N° Permiso</label>
                <input type="text" name="num_permiso" value="{{ $numPermiso }}" readonly>
            </div>

            <div class="col col-2 form-group">
                <label>Fecha Solicitud</label>
                <input type="datetime-local" name="fecha_solicitud">
            </div>
        </div>

        <!-- FILA 2 -->
        <div class="form-group">
            <label>Institución</label>
            <input type="text" name="institucion">
        </div>

        <!-- FILA 3 -->
        <div class="form-group">
            <label>Motivo</label>
            <textarea name="motivo" rows="2"></textarea>
        </div>

        <!-- FILA 4 -->
        <div class="row">
            <div class="col col-3 form-group">
                <label>Fecha Salida</label>
                <input type="date" name="fecha_salida">
            </div>

            <div class="col col-3 form-group">
                <label>Hora Salida</label>
                <input type="time" name="hora_salida">
            </div>

            <div class="col col-3 form-group">
                <label>Hora Retorno</label>
                <input type="time" name="hora_retorno">
            </div>
        </div>

        <!-- FILA 5 -->
        <div class="form-group">
            <label>Destino</label>
            <input type="text" name="destino">
        </div>

        <!-- FILA 6 -->
        <div class="form-group">
            <label>Observaciones</label>
            <textarea name="observaciones" rows="2"></textarea>
        </div>

        <!-- HIDDEN -->
        <input type="hidden" name="gestion_id" value="{{ $gestion_id ?? '' }}">

        <!-- FIRMAS -->
        <div class="firmas">
            <div class="firma">
                <div class="linea"></div>
                Solicitante
            </div>

            <div class="firma">
                <div class="linea"></div>
                Autorización
            </div>
        </div>

    </form>

</div>

@endsection
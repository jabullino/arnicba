@extends('layouts.app')

@section('page_header')
    <h1>Detalle de Entrega de Caja Chica</h1>
@stop

@section('page_content')
<div class="card">
    <div class="card-body">
        @php
            $mesNumero = \Carbon\Carbon::parse($entrega->fecha_entrega)->format('n');
        @endphp

        <div class="form-group mb-3">
            <label class="form-label fw-bold">Mes de Entrega</label>
            <p>{{ $meses[$mesNumero] }}</p>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Fecha de Entrega:</label>
            <p>{{ \Carbon\Carbon::parse($entrega->fecha_entrega)->format('d-m-Y') }}</p>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Monto:</label>
            <p>{{ number_format($entrega->monto, 2) }}</p>
        </div>

        @if (!empty($entrega->descripcion))
        <div class="mb-3">
            <label class="form-label fw-bold">Descripción:</label>
            <p>{{ $entrega->descripcion }}</p>
        </div>
        @endif

        <div class="d-flex justify-content-between mt-4 botones-detalle">
            <a href="{{ route('entregascajachicas.index') }}" class="btn btn-secondary">
                Volver
            </a>

            <a href="{{ route('entregascajachicas.edit', $entrega->id) }}" class="btn btn-warning">
                Editar
            </a>
        </div>
    </div>
</div>

{{-- ====== ESTILOS RESPONSIVE ====== --}}
<style>
.card {
    max-width: 800px;
    margin: 20px auto;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    background-color: #fff;
}

label {
    font-weight: 600;
    margin-bottom: 5px;
    display: block;
}

p {
    margin: 0 0 10px 0;
    font-size: 15px;
}

button, a.btn {
    padding: 8px 12px;
    border-radius: 6px;
    font-size: 15px;
    text-decoration: none;
    text-align: center;
    display: inline-block;
    border: none;
    cursor: pointer;
}

a.btn-secondary {
    background-color: #6c757d;
    color: #fff;
}

a.btn-warning {
    background-color: #ffc107;
    color: #212529;
}

.botones-detalle {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
}

.botones-detalle a {
    flex: 1 1 48%;
    text-align: center;
    margin-bottom: 8px;
}

/* ====== Media Queries ====== */
@media (max-width: 768px) {
    .card {
        padding: 12px;
        margin: 15px;
    }

    p {
        font-size: 14px;
    }

    a.btn {
        font-size: 14px;
        padding: 7px;
    }
}

@media (max-width: 480px) {
    .botones-detalle {
        flex-direction: column;
    }

    .botones-detalle a {
        width: 100%;
        margin-bottom: 8px;
    }

    p {
        font-size: 13px;
    }

    label {
        font-size: 14px;
    }
}
</style>
@stop

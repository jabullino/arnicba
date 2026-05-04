@extends('layouts.app')

@section('content')

<style>
.container {
    max-width: 700px;
    margin: auto;
    color: #fff;
}

.card {
    background: #1e1e1e;
    padding: 15px;
    border-radius: 8px;
    border: 1px solid #444;
}

.row {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.block {
    flex: 1;
    min-width: 120px;
}

.label {
    font-size: 12px;
    color: #aaa;
}

.valor {
    font-weight: bold;
}

.estado {
    padding: 6px;
    text-align: center;
    border-radius: 5px;
    font-weight: bold;
    margin-top: 10px;
}

.pendiente { background: orange; }
.autorizado { background: green; }

.btn {
    width: 100%;
    padding: 10px;
    margin-top: 15px;
    border: none;
    background: #4CAF50;
    color: #fff;
    font-weight: bold;
    border-radius: 5px;
    cursor: pointer;
}
</style>

<div class="container">

<h2>Autorizar Permiso</h2>

<div class="card">

    <div class="row">

        <div class="block">
            <div class="label">N° Solicitud</div>
            <div class="valor">{{ $permiso->num_permiso }}</div>
        </div>

        <div class="block">
            <div class="label">Gestión</div>
            <div class="valor">{{ $permiso->gestion_nombre }}</div>
        </div>

        <div class="block">
            <div class="label">Solicitante</div>
            <div class="valor">
                {{ $permiso->user_nombre }} {{ $permiso->user_apellido }}
            </div>
        </div>

        <div class="block">
            <div class="label">Fecha Salida</div>
            <div class="valor">{{ $permiso->fecha_salida }}</div>
        </div>

        <div class="block">
            <div class="label">Destino</div>
            <div class="valor">{{ $permiso->destino }}</div>
        </div>

    </div>

    <div class="estado {{ $permiso->estado }}">
        {{ strtoupper($permiso->estado) }}
    </div>

    {{-- SOLO SI ESTÁ PENDIENTE --}}
    @if($permiso->estado === 'pendiente')

    <form method="POST" action="{{ route('AdminSis.PermisoSalida.update', $permiso->id)}}">
        @csrf
        @method('PUT')

        <button type="submit" class="btn">
            AUTORIZAR PERMISO
        </button>
    </form>

    @endif

</div>

</div>

@endsection
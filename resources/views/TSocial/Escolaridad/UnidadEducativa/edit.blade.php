@extends('layouts.app')

@section('content')

<div class="card shadow w-[500px] mx-auto mt-4">
    <div class="card-header text-center font-bold">
        EDITAR UNIDAD EDUCATIVA
    </div>

    <div class="card-body">
        <form action="{{ route('UEducativa.update', $unidad->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group mb-3">
                <label for="nombre">Unidad Educativa</label>
                <input type="text" name="nombre" id='nombre' class="form-control" value="{{ $unidad->nombre }}">
            </div>

            <button class="btn btn-primary">Actualizar</button>
            <a href="{{ route('UEducativa.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>

@endsection
@section('js')
<script>
// Selecciona el campo del nombre
const inputNombre = document.getElementById('nombre');

inputNombre.addEventListener('input', function () {

    // Convierte todo a MAYÚSCULAS
    this.value = this.value.toUpperCase();

    // Permite solo: letras, números y espacios
    this.value = this.value.replace(/[^A-Z0-9 ]/g, '');

});
</script>

@endsection
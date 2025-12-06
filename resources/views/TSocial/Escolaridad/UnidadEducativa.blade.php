@extends('layouts.app')

@section('content')

<div class="card shadow w-[500px] mx-auto mb-4">
    <div class="card-header text-white text-center font-bold">
        FORMULARIO PARA LA GESTION DE UNIDADES EDUCATIVAS
    </div>

    <div class="card-body">
        <form action="{{ route('UEducativa.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="nombre">Unidad Educativa</label>
                <input type="text" name="nombre" id="nombre" class="form-control">
            </div>
            <button class="bg-sky-900 text-white text-center text-bold mt-3 w-full h-12 rounded">Guardar</button>
        </form>
    </div>
</div>


{{-- TABLA DE REGISTROS --}}
<div class="card shadow mx-auto w-75">
    <div class="card-header text-center font-bold">
        LISTA DE UNIDADES EDUCATIVAS
    </div>

    <div class="card-body">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($unidades as $unidad)
                <tr>
                    <td>{{ $cont++ }}</td>
                    <td>{{ $unidad->nombre }}</td>

                    <td>
                        <a href="{{ route('UEducativa.edit', $unidad->id) }}" class="btn btn-warning btn-sm">
                            Editar
                        </a>

                        <form action="{{ route('UEducativa.destroy', $unidad->id) }}" 
                              method="POST" 
                              class="d-inline">
                            @csrf
                            @method('DELETE')

                            <button class="btn btn-danger btn-sm btn-delete">
                                Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Éxito',
        text: '{{ session("success") }}',
        timer: 2000,
        showConfirmButton: false
    })
</script>
@endif
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
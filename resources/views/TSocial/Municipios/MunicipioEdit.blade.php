@extends('layouts.app')
@section('content')

<div>
    <div class="p-4">
        <div class="card mx-auto w-full max-w-md">
            <div class="card-header text-center bg-neutral-500">FORMULARIO DE EDICIÓN MUNICIPIOS</div>

            <div class="card-body bg-neutral-500 p-4">
                  <form action="{{route('Municipios.update', $municipio->id)}}" method="POST">
                    @csrf  
                    @method('PATCH')
                    <div>
                    <div class='form-group mb-2'>
                        <label for="id" class="block mb-1">ID</label>
                        <input type="text" name="id" id="id" value='{{$municipio->id}}' readonly>
                    </div>
                    <div class='form-group mb-2'>
                        <label for="municipios" class="block mb-1">Municipio</label>
                        <input type="text" name="municipios" id="municipios" value='{{$municipio->nombre}}'>
                    </div>
                    <div>
                        <button type="submit" class='bg-sky-900 rounded-md w-full'>Editar</button>
                    </div>
                 </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<!-- Incluir SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: '{{ session('success') }}',
            confirmButtonColor: '#3085d6'
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: '¡Error!',
            text: '{{ session('error') }}',
            confirmButtonColor: '#d33'
        });
    @endif
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputMunicipio = document.getElementById('municipios');

    inputMunicipio.addEventListener('input', function() {
        // Convierte todo a mayúsculas
        this.value = this.value.toUpperCase();

        // Solo permite letras y espacios
        this.value = this.value.replace(/[^A-Z\s]/g, '');
    });
});
</script>
@endsection


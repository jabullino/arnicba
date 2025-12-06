@extends('layouts.app')
@section('content')

<div>
    <div class="p-4">
        <div class="card mx-auto w-full max-w-md">
            <div class="card-header text-center bg-neutral-500">FORMULARIO DE REGISTRO DE MUNICIPIOS</div>

            <div class="card-body bg-neutral-500 p-4">
                <form action="{{route('Municipios.store')}}" method="POST">
                    @csrf
                    <div class='form-group mb-2'>
                        <label for="ciudades" class="block mb-1">Ciudad</label>
                        <select name="ciudad_id" id="ciudades" class='form-control w-full'>
                            <option value="">Escoja una Ciudad</option>
                            @foreach ($ciudades as $ciudad)
                                <option value="{{ $ciudad->id }}" {{ old('ciudad_id') == $ciudad->id ? 'selected' : '' }}>
                                    {{ $ciudad->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class='form-group mb-2'>
                        <label for="municipios" class="block mb-1">Municipios</label>
                        <input type="text" name="nombreMunicipio" id="municipios" class='form-control w-full' value="{{ old('nombreMunicipio') }}">
                    </div>

                    <div class='form-group mb-2 w-full'>
                        <button type="submit" id="registrar" class='bg-sky-900 w-full rounded-md py-2 text-white'>
                            Registrar
                        </button>
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


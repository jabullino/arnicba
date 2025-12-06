@extends('layouts.app')
@section('content')
@if ($errors->any())
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Errores en el formulario',
            html: '{!! implode("<br>", $errors->all()) !!}',
        });
    </script>
@endif

@if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Éxito',
            text: '{{ session("success") }}',
        });
    </script>
@endif
<form action="{{ route('Municipios.store') }}" method="POST">
    @csrf
 <div class="card shadow w-[450px] mx-auto">
    <div class="card-header bg-sky-900 text-white bold text-center">
        FORMULARIO PARA EL REGISTRO DE MUNICIPIOS
    </div>
    <div class="card-body">
            <div class='form-group'>
               <label for="ciudad">Ciudad</label>
               <select name="ciudad" id="ciudad" class='form-control'>
                <option value="default">Escoja una ciudad</option>
                @foreach ($ciudades as $ciudad)
                     <option value="{{$ciudad->id}}">{{$ciudad->nombre}}</option>                    
                @endforeach
               </select>
            </div>
            <div class='form-group'>
                 <label for="municipio">Municipio</label>
                 <input type="text" name="municipio" id="municipio" required class='form-control'>
            </div>
            <div>
                <button type="submit" class='bg-sky-900 text-white text-center bold w-full rounded-md h-12'>Registrar</button>
            </div>
    </div>
 </div>
 </form>
@endsection
@section('js')
<script>
    const input = document.getElementById('municipio');

    input.addEventListener('input', function () {
        // Convertir a mayúsculas
        this.value = this.value.toUpperCase();

        // Solo letras, números y espacios
        this.value = this.value.replace(/[^A-Z0-9 ]/g, '');
    });
</script>
@endsection
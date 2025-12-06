@extends('layouts.app')
@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('gestionCreada'))
    @if(session('residentesHtml') && strlen(session('residentesHtml')) > 0)
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Gestión escolar creada y los siguientes alumnos se graduaron',
                html: `{!! session('residentesHtml') !!}`,
                width: 600,
                confirmButtonText: 'Cerrar'
            });
        </script>
    @else
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Gestión escolar creada',
                width: 600,
                confirmButtonText: 'Cerrar'
            });
        </script>
    @endif
@endif
@php
    session()->forget(['gestionCreada','residentesHtml']);
@endphp
<form action="" method='POST'>
    @csrf
<div class="card shadow-md w-[450px] mx-auto">
    <div class="card-header bg-sky-900 text-white text-bold text-center">
          FORMULARIO PARA LA CREACIÓN DE NUEVA GESTION ESCOLAR
    </div>
    <div class="card-body">
             <div class='form-group'>
                <label for="gestion">Gestion Escolar</label>
                <select name="gestion" id="gestion" class='form-control'>
                    <option value="default">Escoja una Gestion</option>
                    @foreach ($gestiones as $gestion)
                          <option value="{{$gestion->id}}">{{$gestion->nombre}}</option>                        
                    @endforeach
                </select>
             </div>
             <div class="form-group">
                <button type="submit" class='bg-sky-900 text-white w-full h-12 rounded-md'>Registrar Gestión</button>
             </div>
    </div>
</div>
</form>
@endsection
@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if (session('error'))
<script>
Swal.fire({
    icon: 'error',
    title: 'Gestión existente',
    text: '{{ session("error") }}',
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

@endsection
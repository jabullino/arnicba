<!-- resources/views/app.blade.php -->
@extends('adminlte::page')
@section('body_class', 'dark-mode') <!-- Esto activa el modo oscuro global -->

@section('content_header')
   <div id='cabecera' class='cabecera'>
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-danger" style="float:right">Salir</button>
        <button id="btnImprimir" class="btn btn-secondary mr-2 text-white bold"
                onclick="event.preventDefault();window.print()" style="float:right">
            Imprimir
        </button>
    </form>
    </div>
@stop

@section('header')
    {{-- Aquí puedes poner un título dinámico desde tus vistas hijas --}}
    @yield('page_header')
@stop

@section('content')
   
    @yield('page_content')
    
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u"
          crossorigin="anonymous">
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.1/css/all.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" crossorigin="anonymous">
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.1/css/all.css" crossorigin="anonymous">
    <!-- SweetAlert2 CSS -->
      
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <style>
 
  table,
 th,
 td {
   background-color:#343a40 !important; ;
    color:#fff;
 }

 .clasecontenedor{
    background-color: #343a40 !important; ;
 }

/* Hace oscuro todo el fondo principal de la app */
body,
.content-wrapper,
.container,
.container-fluid,
.content,
.main-content {
    background-color: #343a40 !important; ; /* mismo color que tu tema */
    color: #fff !important;
}

/* Oscurece también tarjetas, cajas y formularios */
.card,
.box,
.form-control,
.table {
    background-color: #343a40 !important; ;
    color: #fff !important;
    border-color: #444 !important;
}

/* Ajusta inputs y selects */
input,
select,
textarea {
    background-color: #343a40 !important; ;
    color: #fff !important;
    border: 1px solid #555 !important;
}

/* Corrige el fondo blanco que puede aparecer debajo del contenido */
.wrapper,
.content-wrapper {
    background-color: #343a40 !important; ;
}

/* Asegura que los encabezados y cabecera mantengan coherencia */
.content-header,
.cabecera,
.main-header {
    background-color: #343a40 !important; ;
    color: #fff !important;
}

/* El texto de la paginación también en blanco */
.pagination,
.pagination-info,
.pagination .small,
.text-muted {
    color: #fff !important;
}



</style>
@stop

@section('js')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
 @livewireScripts
@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: '¡Éxito!',
        text: '{{ session("success") }}',
        confirmButtonColor: '#3085d6',
    });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: '¡Error!',
        text: '{{ session("error") }}',
        confirmButtonColor: '#d33',
    });
</script>
@endif
@stop
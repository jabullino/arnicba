@extends('layouts.app')
@section('content')
<!-- SweetAlert2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <div class="card"><!--inicio card -->
            <div class="card-header bg-sky-900 text-white text-center font-bold">
                FORMULARIO PARA REAPERTURA DE GESTIÓN
            </div><!-- fin card-header --->
            <div class="card-body">
                <form action="reabreGestion" method='POST'>
                    @csrf
                <div class='mb-2 form-group'>
                    <label for="gestion" class='bg-sky-900 text-white font-bold rounded-md'>Escoja una gestión para reabrir</label>
                    <select name="gestion" id="gestion" class='form-control'>
                        <option value="default">Escoja una gestión</option>
                        @foreach ($gestiones as $gestion)
                            <option value="{{ $gestion->id }}">{{ $gestion->nombre }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="w-full bg-sky-900 text-white mt-2 rounded-md">Reabre Gestion</button>
                </div>
                </form>
            </div><!---fin div card-body -->

        </div><!--fin div card --->

    </div><!----fin div prinicipal --->
@endsection
@section('js')
    <script>
document.addEventListener('DOMContentLoaded', function() {
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: '{{ session('success') }}',
            confirmButtonText: 'Aceptar'
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: '¡Error!',
            text: '{{ session('error') }}',
            confirmButtonText: 'Aceptar'
        });
    @endif

    @if(session('warning'))
        Swal.fire({
            icon: 'warning',
            title: '¡Advertencia!',
            text: '{{ session('warning') }}',
            confirmButtonText: 'Aceptar'
        });
    @endif

    @if(session('info'))
        Swal.fire({
            icon: 'info',
            title: 'Información',
            text: '{{ session('info') }}',
            confirmButtonText: 'Aceptar'
        });
    @endif
});
</script>
@endsection

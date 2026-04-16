@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg rounded-3" style="background-color:#454d55; color:#f8f9fa;">
      @if ($errors->any())
<script>
    let errores = `
        @foreach ($errors->all() as $error)
            • {{ $error }}<br>
        @endforeach
    `;

    Swal.fire({
        icon: 'error',
        title: 'Errores',
        html: errores
    });
</script>
@endif
        <div class="card-body p-4 ">
            <h4 class="text-center mb-4">Nueva Derivación</h4>
            <form action="{{ route('derivaciones.store') }}" method="POST" id="formDerivacion">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6 col-12">
                        <label class="form-label">Residente</label>
                        <select name="residente_id" class="form-select bg-dark text-light border-secondary" required>
                            <option value="">Seleccione un residente</option>
                            @foreach($residentes as $residente)
                                <option value="{{ $residente->id }}">{{ $residente->nombre }} {{ $residente->apellido }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 col-12">
                        <label class="form-label">Fecha</label>
                        <input type="date" name="fecha" class="form-control bg-dark text-light border-secondary" required>
                    </div>
                    <div class="col-md-6 col-12">
                        <label class="form-label">N° Juzgado</label>
                        <input type="text" name="numjuzgado" id="numjuzgado" class="form-control bg-dark text-light border-secondary" required>
                    </div>
                    <div class="col-md-6 col-12">
                        <label class="form-label">N° Documento</label>
                        <input type="text" name="numdoc" id="numdoc" class="form-control bg-dark text-light border-secondary" required>
                    </div>
                    <div class="col-md-6 col-12">
                        <label class="form-label">Nombre del Juez</label>
                        <input type="text" name="nomjuez" id="nomjuez" class="form-control bg-dark text-light border-secondary" required>
                    </div>
                </div>
                <div class="col-12 text-center mt-4">
                    <button type="submit" class="btn btn-success w-50 bg-teal-900">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Éxito',
        text: "{{ session('success') }}",
        confirmButtonColor: '#3085d6'
    });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: "{{ session('error') }}",
        confirmButtonColor: '#d33'
    });
</script>
@endif

@if ($errors->any())
<script>
    let errores = `
        @foreach ($errors->all() as $error)
            • {{ $error }}<br>
        @endforeach
    `;

    Swal.fire({
        icon: 'warning',
        title: 'Errores de validación',
        html: errores,
        confirmButtonColor: '#f0ad4e'
    });
</script>
@endif
@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {

        // Campo N° Juzgado: solo números
        const numJuzgado = document.getElementById('numjuzgado');
        numJuzgado.addEventListener('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        // Campo N° Documento: números, letras, guion, guion bajo, diagonal, convertir a mayúsculas
        const numDoc = document.getElementById('numdoc');
        numDoc.addEventListener('input', function () {
            this.value = this.value.toUpperCase().replace(/[^A-Z0-9_\-\/]/g, '');
        });

        // Campo Nombre del Juez: convertir a mayúsculas, aceptar espacios y punto
        const nomJuez = document.getElementById('nomjuez');
        nomJuez.addEventListener('input', function () {
            this.value = this.value.toUpperCase().replace(/[^A-Z\s.]/g, '');
        });

    });
</script>
@endsection

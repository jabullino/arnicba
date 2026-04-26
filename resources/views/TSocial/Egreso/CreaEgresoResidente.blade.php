@extends('layouts.app')

@section('content')
<div class="container py-3 py-md-4 px-2 px-md-3">
    <div class="card shadow-lg rounded-3 bg-dark text-light">
        
        <div class="card-body p-3 p-md-4">
            <h4 class="text-center mb-4">Nueva Egreso</h4>

            <form action="{{ route('egresoresidente.store') }}" method="POST" id="formDerivacion">
                @csrf

                <div class="row g-3 align-items-stretch">

                    <!-- Gestión -->
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                        <label class="form-label">Gestión</label>
                        <select name="gestion_id" class="form-select bg-dark text-light border-secondary w-100" required>
                            <option value="">Seleccione una gestión</option>
                            @foreach($gestiones as $gestion)
                                <option value="{{ $gestion->id }}">
                                    {{ $gestion->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>   

                    <!-- Fecha -->
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                        <label class="form-label">Fecha</label>
                        <input type="date" name="fecha" class="form-control bg-dark text-light border-secondary w-100" required>
                    </div>                   

                    <!-- Residente -->
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                        <label class="form-label">Residente</label>
                        <select name="residente_id" class="form-select bg-dark text-light border-secondary w-100" required>
                            <option value="">Seleccione un residente</option>
                            @foreach($residentes as $residente)
                                <option value="{{ $residente->id }}">
                                    {{ $residente->nombre }} {{ $residente->apellido }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Motivo -->
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                        <label class="form-label">Motivo</label>
                        <select name="motivo_id" class="form-select bg-dark text-light border-secondary w-100" required>
                            <option value="">Seleccione tipo de egreso</option>
                            @foreach($motivos as $motivo)
                                <option value="{{ $motivo->id }}">
                                    {{ $motivo->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Destino -->
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 mt-2">
                        <label class="form-label">Destino</label>
                        <input type="text" name="destino" id="destino"
                            class="form-control bg-dark text-light border-secondary w-100" disabled>
                    </div>
                
                    <!-- N° Juzgado -->
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 mt-2">
                        <label class="form-label">N° Juzgado</label>
                        <input type="text" name="numjuzgado" id="numjuzgado"
                            class="form-control bg-dark text-light border-secondary w-100" required>
                    </div>

                    <!-- Municipio -->
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                        <label class="form-label">Municipio</label>
                        <select name="municipio_id" class="form-select bg-dark text-light border-secondary w-100" required>
                            <option value="">Seleccione un municipio</option>
                            @foreach($municipios as $municipio)
                                <option value="{{ $municipio->id }}">
                                    {{ $municipio->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- N° Documento -->
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                        <label class="form-label">N° Documento</label>
                        <input type="text" name="numdoc" id="numdoc"
                            class="form-control bg-dark text-light border-secondary w-100" required>
                    </div>

                    <!-- Nombre del Juez -->
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                        <label class="form-label">Nombre del Juez</label>
                        <input type="text" name="nomjuez" id="nomjuez"
                            class="form-control bg-dark text-light border-secondary w-100" required>
                    </div>
                    
                </div>

                <!-- Botón -->
                <div class="mt-4">
                    <button type="submit"
                        class="btn btn-success w-100 w-sm-75 w-md-50 d-block mx-auto">
                        Guardar
                    </button>
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

    // Campo N° Documento
    const numDoc = document.getElementById('numdoc');
    numDoc.addEventListener('input', function () {
        this.value = this.value.toUpperCase().replace(/[^A-Z0-9_\-\/]/g, '');
    });

    // Nombre del Juez
    const nomJuez = document.getElementById('nomjuez');
    nomJuez.addEventListener('input', function () {
        this.value = this.value.toUpperCase().replace(/[^A-Z\s.]/g, '');
    });

});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const motivo = document.querySelector('select[name="motivo_id"]');
    const destino = document.getElementById('destino');

    function toggleDestino() {
        if (motivo.value === '3' || motivo.value === '4') {
            destino.disabled = false;
            destino.required = true;

            destino.style.backgroundColor = '#e8f5e9';
            destino.style.color = '#000';
            destino.placeholder = "Ingrese el destino...";
        } else {
            destino.disabled = true;
            destino.required = false;
            destino.value = '';

            destino.style.backgroundColor = '#212529';
            destino.style.color = '#6c757d';
            destino.placeholder = "Seleccione un motivo válido";
        }
    }

    destino.addEventListener('input', function () {
        this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ ,.]/g, '');
    });

    motivo.addEventListener('change', toggleDestino);

    toggleDestino();
});
</script>
@endsection
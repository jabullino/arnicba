@extends('layouts.app')

@section('content')
<div class="container py-3 py-md-4 px-2 px-md-3">
    <div class="card shadow-lg rounded-3 bg-dark text-light">
        
        <div class="card-body p-3 p-md-4">
            <h4 class="text-center mb-4">Editar Egreso</h4>

            <form action="{{ route('egresoresidente.update', $egreso->id) }}" method="POST" id="formDerivacion">
                @csrf
                @method('PUT')

                <div class="row g-3 align-items-stretch">

                    <!-- Gestión -->
                    <div class="col-12 col-md-6">
                        <label class="form-label">Gestión</label>
                        <select name="gestion_id" class="form-select bg-dark text-light border-secondary w-100" required>
                            <option value="">Seleccione una gestión</option>
                            @foreach($gestiones as $gestion)
                                <option value="{{ $gestion->id }}"
                                    {{ old('gestion_id', $egreso->gestion_id) == $gestion->id ? 'selected' : '' }}>
                                    {{ $gestion->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>   

                    <!-- Fecha -->
                    <div class="col-12 col-md-6">
                        <label class="form-label">Fecha</label>
                        <input type="date" name="fecha"
                            value="{{ old('fecha', $egreso->fecha) }}"
                            class="form-control bg-dark text-light border-secondary w-100" required>
                    </div>                   

                    <!-- Residente -->
                    <div class="col-12 col-md-6">
                        <label class="form-label">Residente</label>
                        <select name="residente_id" class="form-select bg-dark text-light border-secondary w-100" required>
                            <option value="">Seleccione un residente</option>
                            @foreach($residentes as $residente)
                                <option value="{{ $residente->id }}"
                                    {{ old('residente_id', $egreso->residente_id) == $residente->id ? 'selected' : '' }}>
                                    {{ $residente->nombre }} {{ $residente->apellido }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Motivo -->
                    <div class="col-12 col-md-6">
                        <label class="form-label">Motivo</label>
                        <select name="motivo_id" class="form-select bg-dark text-light border-secondary w-100" required>
                            <option value="">Seleccione tipo de egreso</option>
                            @foreach($motivos as $motivo)
                                <option value="{{ $motivo->id }}"
                                    {{ old('motivo_id', $egreso->motivo_id) == $motivo->id ? 'selected' : '' }}>
                                    {{ $motivo->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Destino -->
                    <div class="col-12 col-md-6 mt-2">
                        <label class="form-label">Destino</label>
                        <input type="text" name="destino" id="destino"
                            value="{{ old('destino', $egreso->destino) }}"
                            class="form-control bg-dark text-light border-secondary w-100">
                    </div>
                
                    <!-- N° Juzgado -->
                    <div class="col-12 col-md-6 mt-2">
                        <label class="form-label">N° Juzgado</label>
                        <input type="text" name="numjuzgado" id="numjuzgado"
                            value="{{ old('numjuzgado', $egreso->numjuzgado) }}"
                            class="form-control bg-dark text-light border-secondary w-100" required>
                    </div>

                    <!-- Municipio -->
                    <div class="col-12 col-md-6">
                        <label class="form-label">Municipio</label>
                        <select name="municipio_id" class="form-select bg-dark text-light border-secondary w-100" required>
                            <option value="">Seleccione un municipio</option>
                            @foreach($municipios as $municipio)
                                <option value="{{ $municipio->id }}"
                                    {{ old('municipio_id', $egreso->municipio_id) == $municipio->id ? 'selected' : '' }}>
                                    {{ $municipio->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- N° Documento -->
                    <div class="col-12 col-md-6">
                        <label class="form-label">N° Documento</label>
                        <input type="text" name="numdoc" id="numdoc"
                            value="{{ old('numdoc', $egreso->numdoc) }}"
                            class="form-control bg-dark text-light border-secondary w-100" required>
                    </div>

                    <!-- Nombre del Juez -->
                    <div class="col-12 col-md-6">
                        <label class="form-label">Nombre del Juez</label>
                        <input type="text" name="nomjuez" id="nomjuez"
                            value="{{ old('nomjuez', $egreso->nomjuez) }}"
                            class="form-control bg-dark text-light border-secondary w-100" required>
                    </div>
                    
                </div>

                <!-- Botón -->
                <div class="mt-4">
                    <button type="submit"
                        class="btn btn-warning w-100 w-sm-75 w-md-50 d-block mx-auto">
                        Actualizar
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Éxito',
    text: "{{ session('success') }}"
});
</script>
@endif

@if(session('error'))
<script>
Swal.fire({
    icon: 'error',
    title: 'Error',
    text: "{{ session('error') }}"
});
</script>
@endif

@if ($errors->any())
<script>
Swal.fire({
    icon: 'warning',
    title: 'Errores de validación',
    html: `{!! implode('<br>', $errors->all()) !!}`
});
</script>
@endif

<script>
document.addEventListener('DOMContentLoaded', function () {

    const numJuzgado = document.getElementById('numjuzgado');
    numJuzgado.addEventListener('input', function () {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    const numDoc = document.getElementById('numdoc');
    numDoc.addEventListener('input', function () {
        this.value = this.value.toUpperCase().replace(/[^A-Z0-9_\-\/]/g, '');
    });

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
        } else {
            destino.disabled = true;
            destino.required = false;
            destino.style.backgroundColor = '#212529';
            destino.style.color = '#6c757d';
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
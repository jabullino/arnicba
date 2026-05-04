@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg rounded-3" style="background-color:#454d55; color:#f8f9fa;">
        <div class="card-body p-4">
            <h4 class="text-center mb-4">Editar Derivación</h4>

            <form action="{{ route('derivaciones.update', $derivacion->id) }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="row g-3">
                    <div class="col-md-6 col-12">
                        <label class="form-label">Residente</label>
                        <select name="residente_id" class="form-select bg-dark text-light border-secondary" required>
                            <option value="">Seleccione un residente</option>
                            @foreach($residentes as $residente)
                                <option value="{{ $residente->id }}" {{ $residente->id == $derivacion->residente_id ? 'selected' : '' }}>
                                    {{ $residente->nombre }} {{ $residente->apellido }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @php
                        $gest=$gestion->devuelveNombre($derivacion->gestion_id);
                    @endphp
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                        <label class="form-label">Gestión</label>
                        <select name="gestion_id" class="form-select bg-dark text-light border-secondary w-100" required>
                            <option value="">Seleccione...</option>
                            @foreach($gestiones as $g)
                                <option value="{{ $g->id }}"
                                    {{ $g->id == $derivacion->gestion_id ? 'selected' : '' }}>
                                    {{ $g->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>   

                    <div class="col-md-6 col-12">
                        <label class="form-label">Fecha</label>
                        <input type="date" name="fecha" class="form-control bg-dark text-light border-secondary" value="{{ $derivacion->fecha }}" required>
                    </div>
                    @php
                        $mun=$municipio->devuelveNombre($derivacion->municipio_id);
                    @endphp
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                        <label class="form-label">Municipio</label>
                        <select name="municipio_id" class="form-select bg-dark text-light border-secondary w-100" required>
                            <option value="">Seleccione...</option>
                            @foreach($municipios as $municipio)
                                <option value="{{ $municipio->id }}"
                                    {{ $municipio->id == $derivacion->municipio_id ? 'selected' : '' }}>
                                    {{ $municipio->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 col-12">
                        <label class="form-label">N° Juzgado</label>
                        <input type="text" name="numjuzgado" id="numjuzgado" class="form-control bg-dark text-light border-secondary" value="{{ $derivacion->numjuzgado }}" required>
                    </div>
                    

                    <div class="col-md-6 col-12">
                        <label class="form-label">N° Documento</label>
                        <input type="text" name="numdoc" id="numdoc" class="form-control bg-dark text-light border-secondary" value="{{ $derivacion->numdoc }}" required>
                    </div>

                    <div class="col-md-6 col-12">
                        <label class="form-label">Nombre del Juez</label>
                        <input type="text" name="nomjuez" id="nomjuez" class="form-control bg-dark text-light border-secondary" value="{{ $derivacion->nomjuez }}" required>
                    </div>
                </div>

                <div class="col-12 text-center mt-4 ">
                    <button type="submit" class="btn btn-success w-50 bg-teal-900">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const numjuzgado = document.getElementById('numjuzgado');
    const numdoc = document.getElementById('numdoc');
    const nomjuez = document.getElementById('nomjuez');

    // Solo números en numjuzgado
    numjuzgado.addEventListener('input', function () {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    // numdoc permite números, letras, guion, guion bajo, diagonal y convierte a mayúsculas
    numdoc.addEventListener('input', function () {
        this.value = this.value.toUpperCase().replace(/[^0-9A-Z_\-\/]/g, '');
    });

    // nomjuez convierte a mayúsculas, permite espacios y puntos
    nomjuez.addEventListener('input', function () {
        this.value = this.value.toUpperCase().replace(/[^A-Z\s\.]/g, '');
    });

    // Mensajes con SweetAlert
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: "{{ session('success') }}",
            timer: 2500,
            showConfirmButton: false
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: "{{ session('error') }}",
            timer: 3000,
            showConfirmButton: false
        });
    @endif
});
</script>
@endsection

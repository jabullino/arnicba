@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
        <h3 class="mb-2 mb-md-0">Detalle del Egreso</h3>

        <a href="{{ route('egresoresidente.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <div class="row g-3">

                <!-- Residente -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">Residente</label>
                    <input type="text" class="form-control"
                        value="{{ $egreso->residente->nombre }} {{ $egreso->residente->apellido }}" readonly>
                </div>

                <!-- Fecha -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">Fecha</label>
                    <input type="text" class="form-control"
                        value="{{ \Carbon\Carbon::parse($egreso->fecha)->format('d-m-Y') }}" readonly>
                </div>

                <!-- N° Juzgado -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">N° Juzgado</label>
                    <input type="text" class="form-control"
                        value="{{ $egreso->numjuzgado }}" readonly>
                </div>

                <!-- Municipio -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">Municipio</label>
                    @php
                        $mun = $municipio->devuelveNombre($egreso->municipio_id);
                    @endphp
                    <input type="text" class="form-control"
                        value="{{ $mun }}" readonly>
                </div>

                <!-- Motivo -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">Motivo de Egreso</label>
                    @php
                        $mot = $motivo->devuelveNombre($egreso->motivo_id);
                    @endphp
                    <input type="text" class="form-control"
                        value="{{ $mot }}" readonly>
                </div>

                <!-- Destino -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">Destino</label>
                    <input type="text" class="form-control"
                        value="{{ $egreso->destino }}" readonly>
                </div>

                <!-- N° Documento -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">N° Documento</label>
                    <input type="text" class="form-control"
                        value="{{ $egreso->numdoc }}" readonly>
                </div>

                <!-- Nombre del Juez -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">Nombre del Juez</label>
                    <input type="text" class="form-control"
                        value="{{ $egreso->nomjuez }}" readonly>
                </div>

            </div>

        </div>
    </div>

</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- ✅ MENSAJES --}}
@if(session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: '¡Éxito!',
    text: '{{ session('success') }}',
});
</script>
@endif

@if(session('error'))
<script>
Swal.fire({
    icon: 'error',
    title: 'Error',
    text: '{{ session('error') }}',
});
</script>
@endif

@endsection
@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg rounded-3" style="background-color:#454d55; color:#f8f9fa;">
        <div class="card-body p-4">
            <h4 class="text-center mb-4">Detalle de Derivación</h4>

            <div class="row g-3">

                <div class="col-md-6 col-12">
                    <label class="form-label">Residente</label>
                    <input type="text" class="form-control bg-dark text-light border-secondary"
                        value="{{ $derivacion->residente->nombre }} {{ $derivacion->residente->apellido }}" readonly>
                </div>

                <div class="col-md-6 col-12">
                    <label class="form-label">Gestión</label>
                    <input type="text" class="form-control bg-dark text-light border-secondary"
                        value="{{ $derivacion->gestion->nombre }}" readonly>
                </div>

                <div class="col-md-6 col-12">
                    <label class="form-label">Fecha</label>
                    <input type="text" class="form-control bg-dark text-light border-secondary"
    value="{{ \Carbon\Carbon::parse($derivacion->fecha)->format('d-m-Y') }}" readonly>
                </div>

                <div class="col-md-6 col-12">
                    <label class="form-label">Municipio</label>
                    <input type="text" class="form-control bg-dark text-light border-secondary"
                        value="{{ $derivacion->municipio->nombre }}" readonly>
                </div>

                <div class="col-md-6 col-12">
                    <label class="form-label">N° Juzgado</label>
                    <input type="text" class="form-control bg-dark text-light border-secondary"
                        value="{{ $derivacion->numjuzgado }}" readonly>
                </div>

                <div class="col-md-6 col-12">
                    <label class="form-label">N° Documento</label>
                    <input type="text" class="form-control bg-dark text-light border-secondary"
                        value="{{ $derivacion->numdoc }}" readonly>
                </div>

                <div class="col-md-6 col-12">
                    <label class="form-label">Nombre del Juez</label>
                    <input type="text" class="form-control bg-dark text-light border-secondary"
                        value="{{ $derivacion->nomjuez }}" readonly>
                </div>

            </div>

            <div class="col-12 text-center mt-4">
                <a href="{{ route('derivaciones.index') }}" class="btn btn-secondary w-50">
                    Volver
                </a>
            </div>

        </div>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="container-fluid py-1" style="background-color: #343a40;">
    <div class="d-flex justify-content-center">
        <div class="card shadow-lg rounded-3 w-100" style="background-color: #454d55; color: #f8f9fa; max-width: 900px;">
            <div class="card-body p-4">

                <!-- Datos del Residente -->
                <div class="col-12 text-center mt-4 text-white bg-teal-900 py-2 rounded">
                    DATOS DEL RESIDENTE
                </div>

                <div class="row g-3 mt-3">
                    <div class="col-md-6 col-12">
                        <label class="form-label">Nombre</label>
                        <input type="text" class="form-control bg-dark border-secondary text-light" value="{{ $residente->nombre }}" readonly>
                    </div>
                    <div class="col-md-6 col-12">
                        <label class="form-label">Apellido</label>
                        <input type="text" class="form-control bg-dark border-secondary text-light" value="{{ $residente->apellido }}" readonly>
                    </div>
                    <div class="col-md-6 col-12">
                        <label class="form-label">Carnet</label>
                        <input type="text" class="form-control bg-dark border-secondary text-light" value="{{ $residente->ci }}" readonly>
                    </div>
                    <div class="col-md-6 col-12">
                        <label class="form-label">Extensión</label>
                        <input type="text" class="form-control bg-dark border-secondary text-light" value="{{ $extension }}" readonly>
                    </div>
                    <div class="col-md-6 col-12">
                        <label class="form-label">Ciudad</label>
                        <input type="text" class="form-control bg-dark border-secondary text-light" value="{{ $ciudad }}" readonly>
                    </div>
                    <div class="col-md-6 col-12">
                        <label class="form-label">Provincia</label>
                        <input type="text" class="form-control bg-dark border-secondary text-light" value="{{ $provincia }}" readonly>
                    </div>
                    <div class="col-md-6 col-12">
                        <label class="form-label">Fecha de Ingreso</label>
                        <input type="text" class="form-control bg-dark border-secondary text-light" 
                               value="{{ \Carbon\Carbon::parse($residente->fec_ingreso)->format('d-m-Y') }}" readonly>
                    </div>
                    <div class="col-md-6 col-12">
                        <label class="form-label">Fecha de Egreso</label>
                        <input type="text" class="form-control bg-dark border-secondary text-light" 
                               value="{{ $residente->fec_egreso ? \Carbon\Carbon::parse($residente->fec_egreso)->format('d-m-Y') : '' }}" readonly>
                    </div>
                    <div class="col-md-6 col-12">
                        <label class="form-label">Edad</label>
                        <input type="text" class="form-control bg-dark border-secondary text-light" value="{{ $edad }}" readonly>
                    </div>
                    <div class="col-md-6 col-12">
                        <label class="form-label">Tiempo de estadía</label>
                        <input type="text" class="form-control bg-dark border-secondary text-light" value="{{ $estadia }}" readonly>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Foto</label>
                        @if($residente->foto)
                            <div class="mt-2">
                                <img src="{{ asset('storage/fotos_residentes/' . basename($residente->foto)) }}" 
                                     alt="Foto" class="img-thumbnail" style="width:150px; height:150px; object-fit:cover;">
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Datos de Acogida Circunstancial -->
                <div class="col-12 text-center mt-4 text-white bg-teal-900 py-2 rounded">
                    DATOS DE ACOGIDA CIRCUNSTANCIAL
                </div>

                @if($acogida)
                    <div class="row g-3 mt-3">
                        <div class="col-md-6 col-12">
                            <label class="form-label">Fecha de Ingreso</label>
                            <input type="text" class="form-control bg-dark border-secondary text-light" 
                                   value="{{ \Carbon\Carbon::parse($acogida->fecha)->format('d-m-Y') }}" readonly>
                        </div>
                        <div class="col-md-6 col-12">
                            <label class="form-label">Número de Documento</label>
                            <input type="text" class="form-control bg-dark border-secondary text-light" value="{{ $acogida->numdoc }}" readonly>
                        </div>
                        <div class="col-md-6 col-12">
                            <label class="form-label">Tipología</label>
                            <input type="text" class="form-control bg-dark border-secondary text-light" value="{{ $tipologia }}" readonly>
                        </div>
                        <div class="col-md-6 col-12">
                            <label class="form-label">Ciudad</label>
                            <input type="text" class="form-control bg-dark border-secondary text-light" value="{{ $ciudad_acogida }}" readonly>
                        </div>
                        <div class="col-md-6 col-12">
                            <label class="form-label">Municipio</label>
                            <input type="text" class="form-control bg-dark border-secondary text-light" value="{{ $municipio }}" readonly>
                        </div>
                        <div class="col-md-6 col-12">
                            <label class="form-label">Firmado por</label>
                            <input type="text" class="form-control bg-dark border-secondary text-light" value="{{ $acogida->firmante ?? '' }}" readonly>
                        </div>
                    </div>
                @else
                    <div class="text-center text-muted mt-2">
                        No hay datos de acogida disponibles.
                    </div>
                @endif

                <!-- Botón para editar residente -->
                <div class="col-12 text-center mt-4">
                    <a href="{{ route('residentes.edit', $residente->id) }}" class="btn bg-teal-900 text-light w-50 py-2">
                        Editar Residente
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

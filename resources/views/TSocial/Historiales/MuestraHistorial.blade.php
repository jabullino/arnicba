@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">

    <div class="row justify-content-center">
        <div class="col-12 col-xxl-9">

            <div class="card card-outline card-secondary shadow-sm">
                <div class="card-body p-4">

                    <h3 class="text-center mb-4 fw-bold">
                        Editar Historial
                    </h3>

                    {{-- ERRORES --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('historiales.update', $historial->id) }}" method="POST" id="formReadonly">
                        @csrf
                        @method('PUT')

                        {{-- SELECT RESIDENTE --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                Seleccionar Residente
                            </label>

                            <select name="residente_id" id="residenteSelect" class="form-select form-select-lg" required>

                                @foreach ($residentes as $residente)
                                    <option value="{{ $residente['id'] }}"
                                        data-nombre="{{ $residente['nombre'] }}"
                                        data-apellido="{{ $residente['apellido'] }}"
                                        data-fecnac="{{ $residente['fecnac'] }}"
                                        data-edad="{{ $residente['edad'] }}"
                                        data-fechaingreso="{{ $residente['fecha_ingreso'] }}"
                                        data-estadia="{{ $residente['estadia'] }}"
                                        data-tipologia="{{ $residente['tipologia'] }}"
                                        data-ciudad="{{ $residente['ciudad'] }}"
                                        data-municipio="{{ $residente['municipio'] }}"
                                        {{ $historial->residente_id == $residente['id'] ? 'selected' : '' }}>
                                        {{ $residente['nombre'] }} {{ $residente['apellido'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- CABECERA DINÁMICA --}}
                        <div id="cabeceraResidente" class="border rounded p-3 mb-4">
                            <div class="row g-3">

                                <div class="col-12 col-md-6 col-lg-4">
                                    <strong>Nombre:</strong><br>
                                    <span id="nombreCompleto"></span>
                                </div>

                                <div class="col-6 col-md-3 col-lg-2">
                                    <strong>Edad:</strong><br>
                                    <span id="edad"></span>
                                </div>

                                <div class="col-6 col-md-3 col-lg-3">
                                    <strong>Fec. Nac.:</strong><br>
                                    <span id="fechaNacimiento"></span>
                                </div>

                                <div class="col-12 col-md-6 col-lg-3">
                                    <strong>Fecha Ingreso:</strong><br>
                                    <span id="fechaIngreso"></span>
                                </div>

                                <div class="col-12 col-md-6 col-lg-3">
                                    <strong>Estadía:</strong><br>
                                    <span id="estadia"></span>
                                </div>

                                <div class="col-6 col-md-4 col-lg-2 offset-lg-1">
                                    <strong>Tipología:</strong><br>
                                    <span id="tipologia"></span>
                                </div>

                                <div class="col-6 col-md-4 col-lg-2">
                                    <strong>Ciudad:</strong><br>
                                    <span id="ciudad"></span>
                                </div>

                                <div class="col-6 col-md-4 col-lg-2 ms-lg-auto text-lg-end">
                                    <strong>Municipio:</strong><br>
                                    <span id="municipio"></span>
                                </div>

                            </div>
                        </div>

                        {{-- TÍTULO --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Título</label>
                            <input type="text" name="titulo" id="tituloInput"
                                class="form-control form-control-lg text-center"
                                value="{{ old('titulo', $historial->titulo) }}" required readonly>
                        </div>

                        {{-- CONTENIDO --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Contenido</label>

                            <div style="max-height:400px; overflow-y:auto;">
                                <div id="editor" class="form-control"
                                    style="max-height:400px; overflow-y:auto; background:#fff;">
                                    {!! old('contenido', $historial->contenido) !!}
                                </div>
                            </div>

                            <input type="hidden" name="contenido" id="contenidoInput">
                        </div>
                      
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

{{-- SCRIPT CABECERA DINÁMICA --}}
<script>
document.addEventListener('DOMContentLoaded', function() {

    const select = document.getElementById('residenteSelect');

    function actualizarCabecera() {

        const selected = select.options[select.selectedIndex];
        if (!selected) return;

        const nombreCompleto = selected.dataset.nombre + ' ' + selected.dataset.apellido;

        document.getElementById('nombreCompleto').textContent = nombreCompleto;
        document.getElementById('edad').textContent =
            selected.dataset.edad ? selected.dataset.edad + ' años' : '';
        document.getElementById('fechaNacimiento').textContent =
            selected.dataset.fecnac ?? '';
        document.getElementById('fechaIngreso').textContent =
            selected.dataset.fechaingreso ?? '';
        document.getElementById('estadia').textContent =
            selected.dataset.estadia ?? '';
        document.getElementById('tipologia').textContent =
            selected.dataset.tipologia ?? '';
        document.getElementById('ciudad').textContent =
            selected.dataset.ciudad ?? '';
        document.getElementById('municipio').textContent =
            selected.dataset.municipio ?? '';
    }

    // 🔥 NUEVO: recargar vista con residente_id
    select.addEventListener('change', function() {
        const residenteId = this.value;
        const url = new URL(window.location.href);
        url.searchParams.set('residente_id', residenteId);
        window.location.href = url.toString();
    });

    actualizarCabecera();
});
</script>

{{-- CKEDITOR --}}
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
let editorInstance;

ClassicEditor.create(document.querySelector('#editor'))
    .then(editor => {
        editorInstance = editor;

        editor.enableReadOnlyMode('readonly');

        // 🔥 SOLUCIÓN: cargar contenido correcto
        editor.setData(`{!! addslashes(old('contenido', $historial->contenido)) !!}`);

        editor.model.document.on('change:data', () => {
            document.getElementById('contenidoInput').value = editor.getData();
        });

        document.getElementById('contenidoInput').value = editor.getData();
    });

document.querySelector('form').addEventListener('submit', function() {
    document.getElementById('contenidoInput').value = editorInstance.getData();
});
</script>

{{-- SWEET ALERT --}}
@if (session('success'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Éxito',
            text: "{{ session('success') }}",
            confirmButtonColor: '#3085d6'
        });
    </script>
@endif

@push('css')
<style>
    .ck-editor__editable {
        background-color: #1f2d3d !important;
        color: #ffffff !important;
        min-height: 350px;
    }

    .ck.ck-toolbar {
        background-color: #2b3a4b !important;
        border: 1px solid #3d4b5c !important;
    }

    .ck.ck-button .ck-icon {
        color: #ffffff !important;
        fill: #ffffff !important;
    }

    .ck.ck-button:hover {
        background-color: #3d4b5c !important;
    }

    .ck.ck-button.ck-on {
        background-color: #556ee6 !important;
    }
</style>
@endpush
@endsection

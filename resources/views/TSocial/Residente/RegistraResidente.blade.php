@extends('layouts.app')

@section('content')
<div class="container-fluid py-1" style="background-color: #343a40;">
    <div class="d-flex justify-content-center">
        <div class="card shadow-lg rounded-3 w-100" style="background-color: #454d55; color: #f8f9fa; max-width: 900px;">
            <div class="card-body p-4">
                <div class="col-12 text-center mt-4 text-white bg-teal-900 py-2 rounded">
                        FORMULARIO PARA REGISTRO DE NUEVOS RESIDENTES
                    </div>

                {{-- Mostrar errores de validación --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('residentes.store') }}" method="POST" enctype="multipart/form-data" class="row g-3">
                    @csrf

                    <!-- Nombre -->
                    <div class="col-md-6 col-12">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" class="form-control bg-dark border-secondary text-light" required>
                    </div>

                    <!-- Apellido -->
                    <div class="col-md-6 col-12">
                        <label for="apellido" class="form-label">Apellido</label>
                        <input type="text" id="apellido" name="apellido" value="{{ old('apellido') }}" class="form-control bg-dark border-secondary text-light">
                    </div>

                    <!-- Fecha de nacimiento -->
                    <div class="col-md-6 col-12">
                        <label for="fecnac" class="form-label">Fecha de Nacimiento</label>
                        <input type="date" id="fecnac" name="fecnac" value="{{ old('fecnac') }}" class="form-control bg-dark border-secondary text-light">
                    </div>

                    <!-- Carnet -->
                    <div class="col-md-6 col-12">
                        <label for="ci" class="form-label">Carnet</label>
                        <input type="text" id="ci" name="ci" value="{{ old('ci') }}" class="form-control bg-dark border-secondary text-light">
                    </div>

                    <!-- Extensión -->
                    <div class="col-md-6 col-12 form-group">
                        <label for="extension" class="form-label">Extensión</label>
                        <select id="extension" name="extension" class="form-select bg-dark border-secondary text-light form-control">
                            <option value="">Seleccione una extensión</option>
                            @foreach ($extensiones as $extension)
                                <option value="{{$extension->id}}" {{ old('extension') == $extension->id ? 'selected' : '' }}>
                                    {{$extension->nombre}}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Ciudad -->
                    <div class="col-md-6 col-12 form-group">
                        <label for="ciudad" class="form-label">Ciudad</label>
                        <select id="ciudad" name="ciudad" class="form-select bg-dark border-secondary text-light form-control">
                            <option value="">Seleccione una ciudad</option>
                            @foreach ($ciudades as $ciudad)
                                <option value="{{$ciudad->id}}" {{ old('ciudad') == $ciudad->id ? 'selected' : '' }}>
                                    {{$ciudad->nombre}}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Provincia -->
                    <div class="col-md-6 col-12 form-group">
                        <label for="provincia" class="form-label">Provincia</label>
                        <select id="provincia" name="provincia" class="form-select bg-dark border-secondary text-light form-control">
                            <option value="">Seleccione una provincia</option>
                        </select>
                    </div>

                    <!-- Fecha de ingreso -->
                    <div class="col-md-6 col-12">
                        <label for="fec_ingreso" class="form-label">Fecha de Ingreso</label>
                        <input type="date" id="fec_ingreso" name="fec_ingreso" value="{{ old('fec_ingreso') }}" class="form-control bg-dark border-secondary text-light">
                    </div>

                    <!-- Fecha de egreso -->
                    <div class="col-md-6 col-12">
                        <label for="fec_egreso" class="form-label">Fecha de Egreso</label>
                        <input type="date" id="fec_egreso" name="fec_egreso" value="{{ old('fec_egreso') }}" class="form-control bg-dark border-secondary text-light">
                    </div>

                    <!-- Foto -->
                    <div class="col-12">
                        <label for="foto" class="form-label">Foto</label>
                        <input type="file" id="foto" name="foto" accept="image/*" class="form-control bg-dark border-secondary text-light">
                    </div>

                    <!-- Sección Acogida Circunstancial -->
                    <div class="col-12 text-center mt-4 text-white bg-teal-900 py-2 rounded">
                        DATOS DE ACOGIDA CIRCUNSTANCIAL
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="form-group mb-2">
                            <label for="fechadoc">Fecha de Ingreso</label>
                            <input type="date" name="fechadoc" id="fechadoc" class="form-control bg-dark border-secondary text-light">
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="form-group mb-2">
                            <label for="numdoc">Numero de Documento</label>
                            <input type="text" name="numdoc" id="numdoc" class="form-control bg-dark border-secondary text-light">
                        </div>
                    </div>

                    <!-- Tipología -->
                    <div class="col-md-6 col-12 form-group">
                        <label for="tipologia" class="form-label">Tipología</label>
                        <select id="tipologia" name="tipologia" class="form-select bg-dark border-secondary text-light form-control">
                            <option value="">Seleccione una tipologia</option>
                            @foreach ($tipologias as $tipologia)
                                <option value="{{$tipologia->id}}" {{ old('ciudad_acogida') == $tipologia->id ? 'selected' : '' }}>
                                    {{$tipologia->nombre}}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Ciudad Acogida -->
                    <div class="col-md-6 col-12 form-group">
                        <label for="ciudad_acogida" class="form-label">Ciudad</label>
                        <select id="ciudad_acogida" name="ciudad_acogida" class="form-select bg-dark border-secondary text-light form-control">
                            <option value="">Seleccione una ciudad</option>
                            @foreach ($ciudades as $ciudad)
                                <option value="{{$ciudad->id}}" {{ old('ciudad_acogida') == $ciudad->id ? 'selected' : '' }}>
                                    {{$ciudad->nombre}}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Municipio Acogida -->
                    <div class="col-md-6 col-12 form-group">
                        <label for="municipios_acogida" class="form-label">Municipio</label>
                        <select id="municipios_acogida" name="municipios_acogida" class="form-select bg-dark border-secondary text-light form-control">
                            <option value="">Seleccione un municipio</option>
                        </select>
                    </div>
                    <div class="col-md-6 col-12">
                        <label for="firma" class="form-label">Firmado por:</label>
                        <input type="text" id="firma" name="firma" value="{{ old('firma') }}" class="form-control bg-dark border-secondary text-light">
                    </div>
                    <!-- Botón -->
                    <div class="col-12 text-center mt-3">
                        <button type="submit" class="bg-teal-900 w-100 py-2">
                            Registrar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
{{-- Importar SweetAlert --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Cargar provincias según ciudad
document.getElementById('ciudad').addEventListener('change', function() {
    const ciudadId = this.value;
    const provinciaSelect = document.getElementById('provincia');

    if (ciudadId) {
        provinciaSelect.disabled = false;
        fetch(`/provincias/${ciudadId}`)
            .then(response => response.json())
            .then(data => {
                provinciaSelect.innerHTML = '<option value="">Seleccionar provincia</option>';
                data.forEach(provincia => {
                    const option = document.createElement('option');
                    option.value = provincia.id;
                    option.textContent = provincia.nombre;
                    provinciaSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error al obtener provincias:', error));
    } else {
        provinciaSelect.disabled = true;
        provinciaSelect.innerHTML = '<option value="">Seleccionar provincia</option>';
    }
});

// Mostrar alertas de éxito o error
@if (session('success'))
Swal.fire({
    icon: 'success',
    title: 'Éxito',
    text: '{{ session('success') }}',
    confirmButtonColor: '#3085d6',
    background: '#343a40',
    color: '#f8f9fa'
});
@endif

@if (session('error'))
Swal.fire({
    icon: 'error',
    title: 'Error',
    text: '{{ session('error') }}',
    confirmButtonColor: '#d33',
    background: '#343a40',
    color: '#f8f9fa'
});
@endif
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Script para el primer grupo (normal)
    const ciudadSelect = document.getElementById('ciudad');
    const municipioSelect = document.getElementById('municipios');
    const baseUrl = "{{ url('get-municipios') }}";

    ciudadSelect?.addEventListener('change', function() {
        cargarMunicipios(this.value, municipioSelect);
    });

    // Script para el segundo grupo (acogida)
    const ciudadAcogidaSelect = document.getElementById('ciudad_acogida');
    const municipioAcogidaSelect = document.getElementById('municipios_acogida');

    ciudadAcogidaSelect?.addEventListener('change', function() {
        cargarMunicipios(this.value, municipioAcogidaSelect);
    });

    async function cargarMunicipios(ciudadId, selectDestino) {
        selectDestino.innerHTML = '<option value="">Seleccione un municipio</option>';
        if (!ciudadId) return;
        try {
            const res = await fetch(`${baseUrl}/${ciudadId}`);
            const data = await res.json();
            data.forEach(m => {
                const opt = document.createElement('option');
                opt.value = m.id;
                opt.textContent = m.nombre;
                selectDestino.appendChild(opt);
            });
        } catch (err) {
            console.error('Error al cargar municipios:', err);
        }
    }

    // 🔹 Restricciones solicitadas 🔹

    // Campo "ci" → solo números
    const ciInput = document.getElementById('ci');
    ciInput.addEventListener('keypress', function(e) {
        if (!/[0-9]/.test(e.key)) e.preventDefault();
    });

    // Campo "numdoc"
    const numdocInput = document.getElementById('numdoc');
    numdocInput.addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });
    numdocInput.addEventListener('keypress', function(e) {
        const regex = /[A-Za-z0-9.\-\_\/\s]/; // ← ahora permite punto (.)
        if (!regex.test(e.key)) e.preventDefault();
    });

    // ✅ Campo "firma" → convierte minúsculas a mayúsculas al teclear, permite letras, punto y espacios
    const firmaInput = document.getElementById('firma');
    firmaInput.addEventListener('input', function(e) {
        this.value = this.value.replace(/[^a-zA-Z.\s]/g, '').toUpperCase();
    });

    // Campo "nombre" → convertir letras a mayúsculas al teclear
    const nombreInput = document.getElementById('nombre');
    nombreInput.addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });

    // Campo "apellido" → convertir letras a mayúsculas al teclear
    const apellidoInput = document.getElementById('apellido');
    apellidoInput.addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });
});
</script>
@endsection

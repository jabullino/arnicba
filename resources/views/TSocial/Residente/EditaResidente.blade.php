@extends('layouts.app')

@section('content')
<div class="container-fluid py-1" style="background-color: #343a40;">
    <div class="d-flex justify-content-center">
        <div class="card shadow-lg rounded-3 w-100" style="background-color: #454d55; color: #f8f9fa; max-width: 900px;">
            <div class="card-body p-4">
                <div class="col-12 text-center mt-4 text-white bg-teal-900 py-2 rounded">
                    FORMULARIO PARA EDITAR RESIDENTES
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('residentes.update', $residente->id) }}" method="POST" enctype="multipart/form-data" class="row g-3">
                    @csrf
                    @method('PUT')

                    <!-- Nombre -->
                    <div class="col-md-6 col-12">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $residente->nombre) }}" class="form-control bg-dark border-secondary text-light" required>
                    </div>

                    <!-- Apellido -->
                    <div class="col-md-6 col-12">
                        <label for="apellido" class="form-label">Apellido</label>
                        <input type="text" id="apellido" name="apellido" value="{{ old('apellido', $residente->apellido) }}" class="form-control bg-dark border-secondary text-light">
                    </div>

                    <!-- Fecha de nacimiento -->
                    <div class="col-md-6 col-12">
                        <label for="fecnac" class="form-label">Fecha de Nacimiento</label>
                        <input type="date" id="fecnac" name="fecnac" value="{{ old('fecnac', $residente->fecnac) }}" class="form-control bg-dark border-secondary text-light">
                    </div>

                    <!-- Carnet -->
                    <div class="col-md-6 col-12">
                        <label for="ci" class="form-label">Carnet</label>
                        <input type="text" id="ci" name="ci" value="{{ old('ci', $residente->ci) }}" class="form-control bg-dark border-secondary text-light">
                    </div>

                    <!-- Extensión -->
                    <div class="col-md-6 col-12 form-group">
                        <label for="extension" class="form-label">Extensión</label>
                        <select id="extension" name="extension" class="form-select bg-dark border-secondary text-light form-control">
                            <option value="">Seleccione una extensión</option>
                            @foreach ($extensiones as $extension)
                                <option value="{{$extension->id}}" {{ old('extension', $residente->ext) == $extension->id ? 'selected' : '' }}>
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
                                <option value="{{$ciudad->id}}" {{ old('ciudad', $residente->ciudad) == $ciudad->id ? 'selected' : '' }}>
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
                            @foreach ($provincias as $provincia)
                                <option value="{{ $provincia->id }}" {{ old('provincia', $residente->provincia) == $provincia->id ? 'selected' : '' }}>
                                    {{ $provincia->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Fecha de ingreso -->
                    <div class="col-md-6 col-12">
                        <label for="fec_ingreso" class="form-label">Fecha de Ingreso</label>
                        <input type="date" id="fec_ingreso" name="fec_ingreso" value="{{ old('fec_ingreso', $residente->fec_ingreso) }}" class="form-control bg-dark border-secondary text-light">
                    </div>

                    <!-- Fecha de egreso -->
                    <div class="col-md-6 col-12">
                        <label for="fec_egreso" class="form-label">Fecha de Egreso</label>
                        <input type="date" id="fec_egreso" name="fec_egreso" value="{{ old('fec_egreso', $residente->fec_egreso) }}" class="form-control bg-dark border-secondary text-light">
                    </div>

                    <!-- Foto -->
                    <div class="col-12">
                        <label for="foto" class="form-label">Foto</label>
                        <input type="file" id="foto" name="foto" accept="image/*" class="form-control bg-dark border-secondary text-light">
                        @if($residente->foto)
                            <div class="mt-2">
                                <img src="{{ asset('storage/fotos_residentes/' . basename($residente->foto)) }}" alt="Foto actual" class="img-thumbnail" style="width:150px; height:150px; object-fit:cover;">
                            </div>
                        @endif
                    </div>

                    <!-- Sección Acogida Circunstancial -->
                    <div class="col-12 text-center mt-4 text-white bg-teal-900 py-2 rounded">
                        DATOS DE ACOGIDA CIRCUNSTANCIAL
                    </div>

                    <div class="col-md-6 col-12">
                        <label for="fechadoc">Fecha de Ingreso</label>
                        <input type="date" name="fechadoc" id="fechadoc" class="form-control bg-dark border-secondary text-light" value="{{ old('fechadoc', $acogida?->fecha) }}">
                    </div>

                    <div class="col-md-6 col-12">
                        <label for="numdoc">Número de Documento</label>
                        <input type="text" name="numdoc" id="numdoc" class="form-control bg-dark border-secondary text-light" value="{{ old('numdoc', $acogida?->numdoc) }}">
                    </div>

                    <div class="col-md-6 col-12 form-group">
                        <label for="tipologia" class="form-label">Tipología</label>
                        <select id="tipologia" name="tipologia" class="form-select bg-dark border-secondary text-light form-control">
                            <option value="">Seleccione una tipologia</option>
                            @foreach ($tipologias as $tipologia)
                                <option value="{{$tipologia->id}}" {{ old('tipologia', $acogida?->tipologia) == $tipologia->id ? 'selected' : '' }}>
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
                                <option value="{{$ciudad->id}}" {{ old('ciudad_acogida', $acogida?->ciudad) == $ciudad->id ? 'selected' : '' }}>
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
                            @foreach ($municipiosAcogida as $municipio)
                                <option value="{{ $municipio->id }}" {{ old('municipios_acogida', $acogida?->municipio) == $municipio->id ? 'selected' : '' }}>
                                    {{ $municipio->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 col-12">
                        <label for="firma" class="form-label">Firmado por:</label>
                        <input type="text" id="firma" name="firma" value="{{ old('firma', $acogida?->firmante) }}" class="form-control bg-dark border-secondary text-light">
                    </div>

                    <!-- Botón -->
                    <div class="col-12 text-center mt-3">
                        <button type="submit" class="bg-teal-900 w-100 py-2">
                            Actualizar Residente
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ciudadSelect = document.getElementById('ciudad');
    const provinciaSelect = document.getElementById('provincia');
    const ciudadAcogidaSelect = document.getElementById('ciudad_acogida');
    const municipioAcogidaSelect = document.getElementById('municipios_acogida');
    const baseUrl = "{{ url('get-municipios') }}";

    async function cargarMunicipios(ciudadId, selectDestino, municipioIdSeleccionado = null) {
        selectDestino.innerHTML = '<option value="">Seleccione un municipio</option>';
        if (!ciudadId) return;
        try {
            const res = await fetch(`${baseUrl}/${ciudadId}`);
            const data = await res.json();
            data.forEach(m => {
                const opt = document.createElement('option');
                opt.value = m.id;
                opt.textContent = m.nombre;
                if (municipioIdSeleccionado && municipioIdSeleccionado == m.id) {
                    opt.selected = true;
                }
                selectDestino.appendChild(opt);
            });
        } catch (err) {
            console.error('Error al cargar municipios:', err);
        }
    }

    async function cargarProvincias(ciudadId, selectDestino, provinciaIdSeleccionada = null) {
        selectDestino.innerHTML = '<option value="">Seleccione una provincia</option>';
        if (!ciudadId) return;
        try {
            const res = await fetch(`/provincias/${ciudadId}`);
            const data = await res.json();
            data.forEach(p => {
                const opt = document.createElement('option');
                opt.value = p.id;
                opt.textContent = p.nombre;
                if (provinciaIdSeleccionada && provinciaIdSeleccionada == p.id) {
                    opt.selected = true;
                }
                selectDestino.appendChild(opt);
            });
        } catch (err) {
            console.error('Error al cargar provincias:', err);
        }
    }

    ciudadSelect?.addEventListener('change', function() {
        cargarProvincias(this.value, provinciaSelect);
    });
    ciudadSelect?.addEventListener('change', function() {
        cargarMunicipios(this.value, municipioSelect);
    });
    ciudadAcogidaSelect?.addEventListener('change', function() {
        cargarMunicipios(this.value, municipioAcogidaSelect);
    });

    // Inicializar selects con los valores actuales
    @if($residente->ciudad)
        cargarProvincias({{ $residente->ciudad }}, provinciaSelect, {{ $residente->provincia ?? 'null' }});
        cargarMunicipios({{ $residente->ciudad }}, municipioSelect, {{ $residente->municipio ?? 'null' }});
    @endif

    @if($acogida && $acogida->ciudad)
        cargarMunicipios({{ $acogida->ciudad }}, municipioAcogidaSelect, {{ $acogida->municipio ?? 'null' }});
    @endif
});
</script>
@endsection

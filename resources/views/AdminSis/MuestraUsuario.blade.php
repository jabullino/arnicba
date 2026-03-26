@extends('layouts.app')

@section('content')
<div class="principal p-2">
    <div class='card'>
        <div class='card-header text-center bg-sky-900 text-white bold'>
            FORMULARIO PARA VISTA DE USUARIOS
        </div>

        <form action="{{route('Usuarios.update',$usr->id)}}" method='post' enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <div class='card-body form-grid'>

                <div class='mb-2 form-group'>
                    <label for="nombre">Nombre</label>
                    <input type="text" name='nombre' value="{{ $usr->nombre }}" readonly class='form-control'>
                    @if ($errors->has('nombre'))
                        <div class="error">{{ $errors->first('nombre') }}</div>
                    @endif
                </div>

                <div class='mb-2 form-group'>
                    <label for="apellido">Apellido</label>
                    <input type="text" name='apellido' value="{{ $usr->apellido }}" readonly class='form-control'>
                    @if ($errors->has('apellido'))
                        <div class="error">{{ $errors->first('apellido') }}</div>
                    @endif
                </div>

                <div class='mb-2 form-group'>
                    <label for="fecnac">Fecha de Nacimiento</label>
                    <input type="text" name='fecnac' value="{{ $usr->fecnac }}" readonly class='form-control'>
                    @if ($errors->has('fecnac'))
                        <div class="error">{{ $errors->first('fecnac') }}</div>
                    @endif
                </div>

                <div class='mb-2 form-group'>
                    <label for="ci">C.I.</label>
                    <input type="text" name='ci' value="{{ $usr->ci }}" readonly class='form-control'>
                    @if ($errors->has('ci'))
                        <div class="error">{{ $errors->first('ci') }}</div>
                    @endif
                </div>

                <div class='mb-2 form-group'>
                    <label for="extension">Extensión</label>
                    <select id="extension" name="extension" class='form-control' readonly>
                        <option value="{{$usr->extension_id}}">{{$ext->getExtension($usr->extension_id)}}</option>
                    </select>
                </div>

                <div class='mb-2 form-group'>
                    <label for="ciudad">Ciudad</label>
                    <select id="ciudad" name="ciudad" class='form-control' readonly>
                        <option value="{{$usr->ciudad_id}}">{{$ciu->getCiudad($usr->ciudad_id)}}</option>
                    </select>
                </div>

                <div class='mb-2 form-group'>
                    <label for="provincia">Provincia</label>
                    <select id="provincia" name="provincia" class='form-control' disabled readonly>
                        <option value="{{$usr->provincia_id}}">{{$prov->getProvincia($usr->provincia_id)}}</option>
                    </select>
                </div>

                <div class='mb-2 form-group'>
                    <label for="direccion">Dirección</label>
                    <input type="text" name='direccion' value="{{ $usr->direccion }}" readonly class='form-control'>
                </div>

                <div class='mb-2 form-group'>
                    <label for="referencias">Referencias</label>
                    <input type="text" name='referencias' value="{{ $usr->referencias }}" readonly class='form-control'>
                </div>
                <div id="mapShow" style="height:350px;"></div>
                <div class='mb-2 form-group'>
                    <label for="telefono">Teléfono</label>
                    <input type="text" name='telefono' value="{{ $usr->telefono }}" readonly class='form-control'>
                </div>

                <div class='mb-2 form-group'>
                    <label for="email">Email</label>
                    <input type="text" name='email' value="{{ $usr->email }}" readonly class='form-control'>
                </div>

                <div class='mb-2 form-group'>
                    <label for="cargo">Cargo</label>
                    <select id="cargo" name="cargo" class='form-control' readonly>
                        <option value="{{$usr->cargo_id}}">{{$car->getCargo($usr->cargo_id)}}</option>
                    </select>
                </div>

                <div class='mb-2 form-group'>
                    <label for="fecingreso">Fecha de Ingreso</label>
                    <input type="date" name='fecingreso' value="{{ $usr->fec_ingreso }}" class='form-control' readonly>
                </div>


                <div class='mb-2 form-group'>
                    <button class='bg-sky-900 text-white bold form-control rounded'>Volver</button>
                </div>

            </div><!---fin div card-body------>
        </form>    
    </div><!---fin div class card ---->
</div><!---fin div principal ----->

@section('css')
<style>
.principal {
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}

.documentos-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 0.5rem;
}

.documento-item {
    display: flex;
    align-items: center;
}

.error {
    color: red;
    font-size: 0.875rem;
}

/* --- Media Queries --- */
@media (max-width: 1024px) {
    .form-grid { grid-template-columns: 1fr; }
    .documentos-grid { grid-template-columns: repeat(3, 1fr); }
}

@media (max-width: 768px) {
    .documentos-grid { grid-template-columns: repeat(2, 1fr); }
}

@media (max-width: 480px) {
    .documentos-grid { grid-template-columns: 1fr; }
    .card-body { padding: 1rem; }
}
</style>
@endsection
@section('js')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    const mapShow = L.map('mapShow').setView(
        [{{ $usr->latitud }}, {{ $usr->longitud }}], 
        16
    );

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapShow);

    L.marker([{{ $usr->latitud }}, {{ $usr->longitud }}])
        .addTo(mapShow)
        .bindPopup("Domicilio del usuario")
        .openPopup();
</script>
@endsection

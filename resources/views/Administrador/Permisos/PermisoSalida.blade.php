@extends('layouts.app')

@section('content')

<style>
.form-print {
    width: 100%;
    max-width: 700px;
    margin: auto;
    border: 1px solid #444;
    padding: 12px;
    font-size: 13px;
    background: #121212;
    color: #fff;
}

.header {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
}

.header img { height: 55px; }

label { font-weight: bold; font-size: 12px; }

input, textarea, select {
    width: 100%;
    border: 1px solid #666;
    padding: 6px;
    background: #1e1e1e;
    color: #fff;
    border-radius: 4px;
}

.row {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.col { flex: 1 1 100%; }

@media(min-width:600px){ .col { flex:1; } }
@media(min-width:900px){
    .col-2 { flex:0 0 48%; }
    .col-3 { flex:0 0 32%; }
}

.firmas {
    margin-top: 25px;
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 20px;
}

.firma {
    flex: 1 1 45%;
    text-align: center;
}

.linea {
    margin-top: 40px;
    border-top: 1px solid #aaa;
}

.info-firma {
    margin-top: 6px;
    font-size: 12px;
}

.btn-submit {
    width: 100%;
    padding: 10px;
    margin-top: 15px;
    border: none;
    background: #4CAF50;
    color: #fff;
    font-weight: bold;
    cursor: pointer;
    border-radius: 5px;
}

.btn-submit:hover {
    background: #45a049;
}

@media print {
    .form-print {
        background:#fff;
        color:#000;
    }
}
</style>

<div class="form-print">

<div class="header">
    <img src="{{ asset('imagenes/Logo.svg') }}">

    <div>
        <label>Estado</label>
        <input type="text" value="Pendiente" readonly>
    </div>
</div>

<h3 style="text-align:center;">PERMISO DE SALIDA</h3>

<form method="POST" action="{{ route('PermisoSalida.store') }}">
@csrf

@if(session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Éxito',
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
@if ($errors->any())
<script>
Swal.fire({
    icon: 'error',
    title: 'Errores en el formulario',
    html: `{!! implode('<br>', $errors->all()) !!}`,
    confirmButtonColor: '#d33',
});
</script>
@endif
<div class="row">

    <div class="col col-2">
        <label>Gestión</label>
        <select name="gestion_id">
            @foreach($gestiones as $gestion)
                <option value="{{ $gestion->id }}">{{ $gestion->nombre }}</option>
            @endforeach
        </select>
    </div>

    <div class="col col-2">
        <label>N° Permiso</label>
        <input type="text" name="num_permiso" value="{{ $numPermiso }}" readonly>
    </div>

    <div class="col col-2">
        <label>Fecha Solicitud</label>
        <input type="datetime-local" name="fecha_solicitud">
    </div>

    <div class="col col-2">
        <label>Cargo</label>
        <select name="cargo" id="cargo">
            <option value="">Seleccione</option>
            @foreach($cargos as $cargo)
                <option value="{{ $cargo->id }}">{{ $cargo->nombre }}</option>
            @endforeach
        </select>
    </div>

    <div class="col col-2">
        <label>Solicitante</label>
        <select name="empleado" id="empleado" disabled>
            <option value="" selected disabled>Seleccione un empleado</option>
        </select>
    </div>

</div>

<div>
    <label>Institución</label>
    <input type="text" name="institucion" id="institucion" value="FUNDACIÓN ARCA DE RESCATE DE LOS NIÑOS">
</div>

<div>
    <label>Institución a Visitar</label>
    <input type="text" name="destino" id="destino">
</div>

<div>
    <label>Motivo</label>
    <textarea name="motivo" id="motivo"></textarea>
</div>

<div class="row">
    <div class="col col-3">
        <label>Fecha Salida</label>
        <input type="date" name="fecha_salida">
    </div>

    <div class="col col-3">
        <label>Hora Salida</label>
        <input type="time" name="hora_salida">
    </div>

    <div class="col col-3">
        <label>Hora Retorno</label>
        <input type="time" name="hora_retorno">
    </div>
</div>

<div>
    <label>Observaciones</label>
    <textarea name="observaciones"></textarea>
</div>

<div class="firmas">

    <div class="firma">
        <div class="linea"></div>
        Solicitante
        <div id="infoFirmaSolicitante" class="info-firma"></div>
    </div>

    <div class="firma">
        <div class="linea"></div>
        Autorización
    </div>

</div>

<button type="submit" class="btn-submit">
    REGISTRAR PERMISO
</button>

</form>
</div>

<script>

// 🔠 MAYÚSCULAS
const institucion = document.getElementById('institucion');
const motivo = document.getElementById('motivo');
const destino = document.getElementById('destino');

function upper(e){
    let pos = e.target.selectionStart;
    e.target.value = e.target.value.toUpperCase();
    e.target.setSelectionRange(pos,pos);
}

institucion.addEventListener('input', upper);
motivo.addEventListener('input', upper);
destino.addEventListener('input', upper);


// ===== TU SCRIPT ORIGINAL =====
const cargoSelect = document.getElementById('cargo');
const empleadoSelect = document.getElementById('empleado');
const infoFirma = document.getElementById('infoFirmaSolicitante');

cargoSelect.addEventListener('change', function () {

    let cargoId = this.value;

    empleadoSelect.innerHTML = '<option value="" selected disabled>Seleccione un empleado</option>';
    infoFirma.innerHTML = '';

    if (!cargoId) {
        empleadoSelect.disabled = true;
        return;
    }

    empleadoSelect.disabled = false;

    fetch(`/Administrador/usuarios/${cargoId}`)
        .then(res => res.json())
        .then(data => {

            if (data.length === 0) {
                empleadoSelect.innerHTML = '<option disabled selected>No hay empleados</option>';
                return;
            }

            data.forEach(user => {
                let option = document.createElement('option');
                option.value = user.id;
                option.text = user.nombre + ' ' + user.apellido;

                option.dataset.nombre = user.nombre;
                option.dataset.apellido = user.apellido;
                option.dataset.cargo = user.cargo_nombre;

                empleadoSelect.appendChild(option);
            });

        });
});

empleadoSelect.addEventListener('change', function () {

    let selected = this.options[this.selectedIndex];

    if (!selected || !selected.dataset.nombre) {
        infoFirma.innerHTML = '';
        return;
    }

    let nombre = selected.dataset.nombre;
    let apellido = selected.dataset.apellido;
    let cargo = selected.dataset.cargo;

    infoFirma.innerHTML = `
        <strong>${nombre} ${apellido}</strong><br>
        ${cargo}
    `;
});

</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@endsection

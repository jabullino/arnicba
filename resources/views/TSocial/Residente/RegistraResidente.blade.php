@extends('layouts.app')

@section('content')
<div class="container-fluid py-1" style="background-color:#343a40;">
<div class="d-flex justify-content-center">

<div class="card shadow-lg rounded-3 w-100" style="background-color:#454d55;color:#f8f9fa;max-width:900px;">
<div class="card-body p-4">

<div class="col-12 text-center mt-4 text-white bg-teal-900 py-2 rounded">
FORMULARIO PARA REGISTRO DE NUEVOS RESIDENTES
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

<form action="{{ route('residentes.store') }}" method="POST" enctype="multipart/form-data" class="row g-3">
@csrf

<div class="col-md-6 col-12">
<label class="form-label">Nombre</label>
<input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}"
class="form-control bg-dark border-secondary text-light" required>
</div>

<div class="col-md-6 col-12">
<label class="form-label">Apellido</label>
<input type="text" id="apellido" name="apellido" value="{{ old('apellido') }}"
class="form-control bg-dark border-secondary text-light">
</div>

<div class="col-md-6 col-12">
<label class="form-label">Fecha de Nacimiento</label>
<input type="date" name="fecnac" value="{{ old('fecnac') }}"
class="form-control bg-dark border-secondary text-light">
</div>

<div class="col-md-6 col-12">
<label class="form-label">Carnet</label>
<input type="text" id="ci" name="ci" value="{{ old('ci') }}"
class="form-control bg-dark border-secondary text-light">
</div>

<div class="col-md-6 col-12">
<label class="form-label">Extensión</label>
<select name="extension" class="form-select bg-dark border-secondary text-light">
<option value="">Seleccione</option>
@foreach ($extensiones as $extension)
<option value="{{$extension->id}}">{{$extension->nombre}}</option>
@endforeach
</select>
</div>

<div class="col-md-6 col-12">
<label class="form-label">Ciudad</label>
<select id="ciudad" name="ciudad" class="form-select bg-dark border-secondary text-light">
<option value="">Seleccione una ciudad</option>
@foreach ($ciudades as $ciudad)
<option value="{{$ciudad->id}}">{{$ciudad->nombre}}</option>
@endforeach
</select>
</div>

<div class="col-md-6 col-12">
<label class="form-label">Provincia</label>
<select id="provincia" name="provincia"
class="form-select bg-dark border-secondary text-light">
<option value="">Seleccione provincia</option>
</select>
</div>

<div class="col-md-6 col-12">
<label class="form-label">Fecha de Ingreso</label>
<input type="date" name="fec_ingreso"
class="form-control bg-dark border-secondary text-light">
</div>

<div class="col-md-6 col-12">
<label class="form-label">Fecha de Egreso</label>
<input type="date" name="fec_egreso"
class="form-control bg-dark border-secondary text-light">
</div>

<div class="col-12">
<label class="form-label">Foto</label>
<input type="file" name="foto" accept="image/*"
class="form-control bg-dark border-secondary text-light">
</div>

<div class="col-12 text-center mt-4 text-white bg-teal-900 py-2 rounded">
DATOS DE ACOGIDA CIRCUNSTANCIAL
</div>

<div class="col-md-6 col-12">
<label>Fecha de Ingreso</label>
<input type="date" name="fechadoc" id="fechadoc"
class="form-control bg-dark border-secondary text-light">
</div>

<div class="col-md-6 col-12">
<label>Numero de Documento</label>
<input type="text" id="numdoc" name="numdoc"
class="form-control bg-dark border-secondary text-light">
</div>

<div class="col-md-6 col-12">
<label>Tipología</label>
<select name="tipologia"
class="form-select bg-dark border-secondary text-light">
<option value="">Seleccione</option>
@foreach ($tipologias as $tipologia)
<option value="{{$tipologia->id}}">{{$tipologia->nombre}}</option>
@endforeach
</select>
</div>

<div class="col-md-6 col-12">
<label>Ciudad</label>
<select id="ciudad_acogida" name="ciudad_acogida"
class="form-select bg-dark border-secondary text-light">

<option value="">Seleccione ciudad</option>

@foreach ($ciudades as $ciudad)
<option value="{{$ciudad->id}}">{{$ciudad->nombre}}</option>
@endforeach

</select>
</div>

<div class="col-md-6 col-12">
<label>Municipio</label>

<select id="municipios_acogida" name="municipios_acogida"
class="form-select bg-dark border-secondary text-light">

<option value="">Seleccione municipio</option>

</select>

</div>

<div class="col-md-6 col-12">
<label>Firmado por</label>
<input type="text" id="firma" name="firma"
class="form-control bg-dark border-secondary text-light">
</div>

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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

document.addEventListener('DOMContentLoaded',function(){

/* PROVINCIAS */

let ciudad=document.getElementById('ciudad');
let provincia=document.getElementById('provincia');

ciudad?.addEventListener('change',function(){

let ciudadId=this.value;

provincia.innerHTML='<option value="">Seleccione provincia</option>';

if(ciudadId==='')return;

fetch(`/provincias/${ciudadId}`)

.then(res=>res.json())

.then(data=>{

data.forEach(p=>{

let option=document.createElement('option');

option.value=p.id;
option.textContent=p.nombre;

provincia.appendChild(option);

});

});

});


/* MUNICIPIOS */

let ciudadAcogida=document.getElementById('ciudad_acogida');
let municipio=document.getElementById('municipios_acogida');

ciudadAcogida?.addEventListener('change',function(){

let ciudadId=this.value;

municipio.innerHTML='<option value="">Seleccione municipio</option>';

if(ciudadId==='')return;

fetch("{{ route('getMunicipios','') }}/"+ciudadId)

.then(res=>res.json())

.then(data=>{

data.forEach(m=>{

let option=document.createElement('option');

option.value=m.id;
option.textContent=m.nombre;

municipio.appendChild(option);

});

});

});


/* VALIDACIONES */

document.getElementById('ci')?.addEventListener('keypress',e=>{
if(!/[0-9]/.test(e.key))e.preventDefault();
});

document.getElementById('numdoc')?.addEventListener('input',function(){
this.value=this.value.toUpperCase();
});

document.getElementById('nombre')?.addEventListener('input',function(){
this.value=this.value.toUpperCase();
});

document.getElementById('apellido')?.addEventListener('input',function(){
this.value=this.value.toUpperCase();
});

document.getElementById('firma')?.addEventListener('input',function(){
this.value=this.value.replace(/[^A-Za-z.\s]/g,'').toUpperCase();
});

});

</script>


@if(session('success'))

<script>

Swal.fire({
icon:'success',
title:'Éxito',
text:'{{ session('success') }}',
background:'#343a40',
color:'#fff'
});

</script>

@endif


@if(session('error'))

<script>

Swal.fire({
icon:'error',
title:'Error',
text:'{{ session('error') }}',
background:'#343a40',
color:'#fff'
});

</script>

@endif

@endsection

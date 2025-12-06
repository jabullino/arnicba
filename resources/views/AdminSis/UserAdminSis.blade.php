@extends('layouts.app')

@section('content')
<div> <!---div principal --->
    <div class='card'>
        @if (session('CrearUsuarioAdmin'))
            <div class="alert alert-danger text-center">
                {{ session('CrearUsuarioAdmin') }}
            </div>
        @endif

        <div class='card-header text-center bg-sky-900 text-white bold'>
            FORMULARIO PARA REGISTRO DEL ADMINISTRADOR DE SISTEMA
        </div>

        <form action="{{route('CreaAdminSis')}}" method='POST' enctype="multipart/form-data">
            @csrf
            <div class='card-body grid-form'>

                <div class='mb-2 form-group'>
                    <label for="nombre">Nombre</label>
                    <input type="text" name='nombre' class='form-control'>
                    @if ($errors->has('nombre'))
                        <div style="color: red;">{{ $errors->first('nombre') }}</div>
                    @endif
                </div>

                <div class='mb-2 form-group'>
                    <label for="apellido">Apellido</label>
                    <input type="text" name='apellido' class='form-control'>
                    @if ($errors->has('apellido'))
                        <div style="color: red;">{{ $errors->first('apellido') }}</div>
                    @endif
                </div>

                <div class='mb-2 form-group'>
                    <label for="fecnac">Fecha de Nacimiento</label>
                    <input type="date" name='fecnac' class='form-control'>
                    @if ($errors->has('fecnac'))
                        <div style="color: red;">{{ $errors->first('fecnac') }}</div>
                    @endif
                </div>

                <div class='mb-2 form-group'>
                    <label for="ci">C.I.</label>
                    <input type="text" name='ci' class='form-control'>
                    @if ($errors->has('ci'))
                        <div style="color: red;">{{ $errors->first('ci') }}</div>
                    @endif
                </div>

                <div class='mb-2 form-group'>
                    <label for="extension">Extensión</label>
                    <select id="extension" name="extension" class='form-control'>
                        <option value="">Seleccionar Extension</option>
                        @foreach ($extensiones as $extension)
                            <option value="{{ $extension->id }}">{{ $extension->nombre }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('extension'))
                        <div style="color: red;">{{ $errors->first('extension') }}</div>
                    @endif
                </div>

                <div class='mb-2 form-group'>
                    <label for="ciudad">Ciudad</label>
                    <select id="ciudad" name="ciudad" class='form-control'>
                        <option value="">Seleccionar ciudad</option>
                        @foreach ($ciudades as $ciudad)
                            <option value="{{ $ciudad->id }}">{{ $ciudad->nombre }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('ciudad'))
                        <div style="color: red;">{{ $errors->first('ciudad') }}</div>
                    @endif
                </div>

                <div class='mb-2 form-group'>
                    <label for="provincia">Provincia</label>
                    <select id="provincia" name="provincia" class='form-control' disabled>
                        <option value="">Seleccionar provincia</option>
                    </select>
                    @if ($errors->has('provincia'))
                        <div style="color: red;">{{ $errors->first('provincia') }}</div>
                    @endif
                </div>

                <div class='mb-2 form-group'>
                    <label for="direccion">Dirección</label>
                    <input type="text" name='direccion' class='form-control'>
                    @if ($errors->has('direccion'))
                        <div style="color: red;">{{ $errors->first('direccion') }}</div>
                    @endif
                </div>

                <div class='mb-2 form-group'>
                    <label for="referencias">Referencias</label>
                    <input type="text" name='referencias' class='form-control'>
                    @if ($errors->has('referencias'))
                        <div style="color: red;">{{ $errors->first('referencias') }}</div>
                    @endif
                </div>

                <div class='mb-2 form-group'>
                    <label for="telefono">Teléfono</label>
                    <input type="text" name='telefono' class='form-control'>
                    @if ($errors->has('telefono'))
                        <div style="color: red;">{{ $errors->first('telefono') }}</div>
                    @endif
                </div>

                <div class='mb-2 form-group'>
                    <label for="email">Email</label>
                    <input type="text" name='email' class='form-control'>
                    @if ($errors->has('email'))
                        <div style="color: red;">{{ $errors->first('email') }}</div>
                    @endif
                </div>

                <div class='mb-2 form-group'>
                    <label for="password">Password</label>
                    <input type="password" name='password' class='form-control'>
                    @if ($errors->has('password'))
                        <div style="color: red;">{{ $errors->first('password') }}</div>
                    @endif
                </div>

                <div class='mb-2 form-group'>
                    <label for="password_confirmation">Confirmar password</label>
                    <input type="password" name='password_confirmation' class='form-control'>
                    @if ($errors->has('password_confirmation'))
                        <div style="color: red;">{{ $errors->first('password_confirmation') }}</div>
                    @endif
                </div>

                <div class='mb-2 form-group'>
                    <label for="cargo">Cargo</label>
                    <select id="cargo" name="cargo" class='form-control'>
                        <option value="">Seleccionar cargo</option>
                        <option value="1">Admin. Sistema</option>
                    </select>
                    @if ($errors->has('cargo'))
                        <div style="color: red;">{{ $errors->first('cargo') }}</div>
                    @endif
                </div>

                <div class='mb-2 form-group'>
                    <button class='submit-btn'>Registrar</button>
                </div>

            </div><!---fin div card-body------->
        </form>    
    </div><!---fin div class card ---->
</div><!---fin div principal ----->

<script>
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
</script>

@endsection

@section('css')
<style>
.grid-form {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}

.form-control {
    width: 100%;
    padding: 0.25rem;
    box-sizing: border-box;
}

.submit-btn {
    background-color: #0ea5e9;
    color: white;
    font-weight: bold;
    border-radius: 0.25rem;
    padding: 0.5rem;
    width: 100%;
}

/* Media Queries */
@media (max-width: 768px) {
    .grid-form {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection

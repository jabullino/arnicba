@extends('layouts.app')
@section('content')
<div class="usuario-edit-container">
    <div class="card">
        <div class="card-header text-center bg-sky-900 text-white bold">
            FORMULARIO PARA EDICIÓN DE USUARIOS
        </div>
        <form action="{{ route('Usuarios.update', $usr->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <div class="card-body form-grid">
                <!-- Campos de usuario -->
                <div class="mb-2 form-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" name='nombre' value="{{ $usr->nombre }}" class='form-control'>
                    @error('nombre')<div class="error-text">{{ $message }}</div>@enderror
                </div>

                <div class="mb-2 form-group">
                    <label for="apellido">Apellido</label>
                    <input type="text" name='apellido' value="{{ $usr->apellido }}" class='form-control'>
                    @error('apellido')<div class="error-text">{{ $message }}</div>@enderror
                </div>

                <div class="mb-2 form-group">
                    <label for="fecnac">Fecha de Nacimiento</label>
                    <input type="date" name='fecnac' value="{{ $usr->fecnac }}" class='form-control'>
                    @error('fecnac')<div class="error-text">{{ $message }}</div>@enderror
                </div>

                <div class="mb-2 form-group">
                    <label for="ci">C.I.</label>
                    <input type="text" name='ci' value="{{ $usr->ci }}" class='form-control'>
                    @error('ci')<div class="error-text">{{ $message }}</div>@enderror
                </div>

                <!-- Selects -->
                <div class="mb-2 form-group">
                    <label for="extension">Extensión</label>
                    <select id="extension" name="extension" class='form-control'>
                        <option value="{{ $usr->extension_id }}">{{ $ext->getExtension($usr->extension_id) }}</option>
                        @foreach ($extensiones as $extension)
                            <option value="{{ $extension->id }}">{{ $extension->nombre }}</option>
                        @endforeach
                    </select>
                    @error('extension')<div class="error-text">{{ $message }}</div>@enderror
                </div>

                <div class="mb-2 form-group">
                    <label for="ciudad">Ciudad</label>
                    <select id="ciudad" name="ciudad" class='form-control'>
                        <option value="{{ $usr->ciudad_id }}">{{ $ciu->getCiudad($usr->ciudad_id) }}</option>
                        @foreach ($ciudades as $ciudad)
                            <option value="{{ $ciudad->id }}">{{ $ciudad->nombre }}</option>
                        @endforeach
                    </select>
                    @error('ciudad')<div class="error-text">{{ $message }}</div>@enderror
                </div>

                <div class="mb-2 form-group">
                    <label for="provincia">Provincia</label>
                    <select id="provincia" name="provincia" class='form-control'>
                        <option value="{{ $usr->provincia_id }}">{{ $prov->getProvincia($usr->provincia_id) }}</option>
                    </select>
                    @error('provincia')<div class="error-text">{{ $message }}</div>@enderror
                </div>

                <!-- Otros campos de usuario -->
                <div class="mb-2 form-group">
                    <label for="direccion">Dirección</label>
                    <input type="text" name='direccion' value="{{ $usr->direccion }}" class='form-control'>
                    @error('direccion')<div class="error-text">{{ $message }}</div>@enderror
                </div>

                <div class="mb-2 form-group">
                    <label for="telefono">Teléfono</label>
                    <input type="text" name='telefono' value="{{ $usr->telefono }}" class='form-control'>
                    @error('telefono')<div class="error-text">{{ $message }}</div>@enderror
                </div>

                <div class="mb-2 form-group">
                    <label for="email">Email</label>
                    <input type="text" name='email' value="{{ $usr->email }}" class='form-control'>
                    @error('email')<div class="error-text">{{ $message }}</div>@enderror
                </div>

                <div class="mb-2 form-group">
                    <label for="password">Password</label>
                    <input type="password" name='password' class='form-control'>
                    @error('password')<div class="error-text">{{ $message }}</div>@enderror
                    <ul id="password-rules" style="font-size:13px; margin-top:5px; list-style:none; padding-left:0;background-color:#0f172a">
                        <li id="rule-length" style="color:white;">• Mínimo 8 caracteres</li>
                        <li id="rule-mayus" style="color:white;">• Al menos 1 letra mayúscula</li>
                        <li id="rule-number" style="color:white;">• Al menos 1 número</li>
                        <li id="rule-symbol" style="color:white;">• Al menos 1 símbolo (* - _ @ ! etc.)</li>
                    </ul>
                </div>

                <div class="mb-2 form-group">
                    <label for="password_confirmation">Confirmar password</label>
                    <input type="password" name='password_confirmation' class='form-control'>
                    @error('password_confirmation')<div class="error-text">{{ $message }}</div>@enderror
                </div>

                <div class="mb-2 form-group">
                    <label for="cargo">Cargo</label>
                    <select id="cargo" name="cargo" class='form-control'>
                        <option value="{{ $usr->cargo_id }}">{{ $car->getCargo($usr->cargo_id) }}</option>
                        @foreach ($cargos as $car)
                            <option value="{{ $car->id }}">{{ $car->nombre }}</option>
                        @endforeach
                    </select>
                    @error('cargo')<div class="error-text">{{ $message }}</div>@enderror
                </div>

                <div class="mb-2 form-group">
                    <label for="fecingreso">Fecha de Ingreso</label>
                    <input type="date" name='fecingreso' value="{{ $usr->fec_ingreso }}" class='form-control'>
                    @error('fecingreso')<div class="error-text">{{ $message }}</div>@enderror
                </div>

                <div class="mb-2 form-group">
                    <label for="fec_egreso">Fecha de Egreso</label>
                    <input type="date" name='fec_egreso' value="{{ $usr->fec_egreso }}" class='form-control'>
                    @error('fecegreso')<div class="error-text">{{ $message }}</div>@enderror
                </div>

                <div class="mb-2 form-group">
                    <label for="foto">Foto</label>
                    <input type="file" name='foto' class='form-control'>
                    @error('foto')<div class="error-text">{{ $message }}</div>@enderror
                </div>

                <div class="mb-2 form-group">
                    <button class="btn-submit w-full">Actualizar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
.usuario-edit-container {
    max-width: 1050px;
    margin: 30px auto;
    padding: 0 15px;
}

.card {
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    padding: 15px;
}

.card-header {
    font-weight: bold;
    font-size: 18px;
    text-align: center;
    margin-bottom: 15px;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 10px;
}

.form-control {
    width: 100%;
    padding: 8px 10px;
    border-radius: 6px;
    border: 1px solid #ccc;
    box-sizing: border-box;
}

.btn-submit {
    background-color: #0ea5e9; /* sky-900 */
    color: white;
    font-weight: bold;
    padding: 10px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}

.error-text {
    color: red;
    font-size: 14px;
    margin-top: 2px;
}

/* Media Queries */
@media (max-width: 1024px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    .card-header {
        font-size: 16px;
    }
    .form-control {
        padding: 6px;
        font-size: 14px;
    }
    .btn-submit {
        padding: 8px;
        font-size: 14px;
    }
}
</style>

<script>
document.getElementById('ciudad').addEventListener('change', function() {
    const ciudadId = this.value;
    const provinciaSelect = document.getElementById('provincia');

    if (ciudadId) {
        provinciaSelect.disabled = false;
        fetch(`/AdminSis/provincias/${ciudadId}`)
            .then(res => res.json())
            .then(data => {
                provinciaSelect.innerHTML = '<option value="">Seleccionar provincia</option>';
                data.forEach(p => {
                    const option = document.createElement('option');
                    option.value = p.id;
                    option.textContent = p.nombre;
                    provinciaSelect.appendChild(option);
                });
            }).catch(err => console.error(err));
    } else {
        provinciaSelect.disabled = true;
        provinciaSelect.innerHTML = '<option value="">Seleccionar provincia</option>';
    }
});
</script>
<script>
        document.addEventListener("DOMContentLoaded", function() {

            const passwordInput = document.querySelector('input[name="password"]');

            if (!passwordInput) return;

            passwordInput.addEventListener('input', function() {
                const value = passwordInput.value;

                // Validaciones
                const lengthOK = value.length >= 8;
                const mayusOK = /[A-Z]/.test(value);
                const numberOK = /\d/.test(value);
                const symbolOK = /[\W_]/.test(value); // incluye *, -, _, etc.

                // Cambiar color según cumplimiento
                document.getElementById("rule-length").style.color = lengthOK ? "green" : "red";
                document.getElementById("rule-mayus").style.color = mayusOK ? "green" : "red";
                document.getElementById("rule-number").style.color = numberOK ? "green" : "red";
                document.getElementById("rule-symbol").style.color = symbolOK ? "green" : "red";
            });

        });
    </script>
@endsection

@extends('layouts.app')
@section('content')
    <div class="usuario-form-container">
        <div class="card">
            <div class="card-header text-center bg-sky-900 text-white bold">
                FORMULARIO PARA REGISTRO DE USUARIOS
            </div>
            <form action="{{ route('Usuarios.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body form-grid">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" name="nombre" id='nombre' value="{{ old('nombre') }}" class="form-control">
                        @error('nombre')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="apellido">Apellido</label>
                        <input type="text" name="apellido" id='apellido' value="{{ old('apellido') }}"
                            class="form-control">
                        @error('apellido')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="fecnac">Fecha de Nacimiento</label>
                        <input type="date" name="fecnac" value="{{ old('fecnac') }}" class="form-control">
                        @error('fecnac')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="ci">C.I.</label>
                        <input type="text" name="ci" value="{{ old('ci') }}" class="form-control">
                        @error('ci')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="extension">Extensión</label>
                        <select name="extension" id="extension" class="form-control">
                            <option value="">Seleccionar Extension</option>
                            @foreach ($extensiones as $extension)
                                <option value="{{ $extension->id }}">{{ $extension->nombre }}</option>
                            @endforeach
                        </select>
                        @error('extension')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="ciudad">Ciudad</label>
                        <select name="ciudad" id="ciudad" class="form-control">
                            <option value="">Seleccionar ciudad</option>
                            @foreach ($ciudades as $ciudad)
                                <option value="{{ $ciudad->id }}">{{ $ciudad->nombre }}</option>
                            @endforeach
                        </select>
                        @error('ciudad')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="provincia">Provincia</label>
                        <select name="provincia" id="provincia" class="form-control" disabled>
                            <option value="">Seleccionar provincia</option>
                        </select>
                        @error('provincia')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="direccion">Dirección</label>
                        <input type="text" name="direccion" id='direccion'value="{{ old('direccion') }}"
                            class="form-control">
                        @error('direccion')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="referencias">Referencias</label>
                        <input type="text" name="referencias" id='referencias' value="{{ old('referencias') }}"
                            class="form-control">
                        @error('referencias')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="telefono">Teléfono</label>
                        <input type="text" name="telefono" value="{{ old('telefono') }}" class="form-control">
                        @error('telefono')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" name="email" value="{{ old('email') }}" class="form-control">
                        @error('email')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" class="form-control">
                        @error('password')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                        <ul id="password-rules" style="font-size:13px; margin-top:5px; list-style:none; padding-left:0;background-color:#0f172a">
                        <li id="rule-length" style="color:white;">• Mínimo 8 caracteres</li>
                        <li id="rule-mayus" style="color:white;">• Al menos 1 letra mayúscula</li>
                        <li id="rule-number" style="color:white;">• Al menos 1 número</li>
                        <li id="rule-symbol" style="color:white;">• Al menos 1 símbolo (* - _ @ ! etc.)</li>
                    </ul>
                    </div>
                    
                    <div class="form-group">
                        <label for="password_confirmation">Confirmar password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                        @error('password_confirmation')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="cargo">Cargo</label>
                        <select name="cargo" id="cargo" class="form-control">
                            <option value="">Seleccionar cargo</option>
                            @foreach ($cargos as $car)
                                <option value="{{ $car->id }}">{{ $car->nombre }}</option>
                            @endforeach
                        </select>
                        @error('cargo')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="fecingreso">Fecha de Ingreso</label>
                        <input type="date" name="fecingreso" value="{{ old('fecingreso') }}" class="form-control">
                        @error('fecingreso')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="foto">Foto</label>
                        <input type="file" name="foto" class="form-control">
                        @error('foto')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-sky w-full">Registrar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ====== ESTILOS RESPONSIVE ====== --}}
    <style>
        .usuario-form-container {
            max-width: 900px;
            margin: 50px auto 20px auto;
            padding: 0 15px;
        }

        .card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            padding: 15px;
        }

        .card-header {
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 15px;
            text-align: center;
            padding: 10px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 8px 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        .error-message {
            color: red;
            font-size: 13px;
            margin-top: 2px;
        }

        button.btn {
            padding: 10px 12px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-weight: bold;
            font-size: 15px;
            background-color: #0f172a;
            color: #fff;
        }

        /* ===== Media Queries ===== */
        @media (max-width: 1024px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .card-header {
                font-size: 16px;
            }

            button.btn {
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            .usuario-form-container {
                margin: 30px 10px;
            }

            .card-header {
                font-size: 15px;
            }

            button.btn {
                font-size: 13px;
                padding: 8px;
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

    <script>
        document.getElementById('nombre').addEventListener('input', function(e) {
            let valor = e.target.value;

            // Eliminar caracteres no permitidos (todo lo que no sea letra o espacio)
            valor = valor.replace(/[^a-zA-ZÀ-ÿ\s]/g, '');

            // Convertir a mayúsculas
            valor = valor.toUpperCase();

            // Actualizar el valor del input
            e.target.value = valor;
        });
        document.getElementById('apellido').addEventListener('input', function(e) {
            let valor = e.target.value;

            // Eliminar caracteres no permitidos (todo lo que no sea letra o espacio)
            valor = valor.replace(/[^a-zA-ZÀ-ÿ\s]/g, '');

            // Convertir a mayúsculas
            valor = valor.toUpperCase();

            // Actualizar el valor del input
            e.target.value = valor;
        });
        document.getElementById('direccion').addEventListener('input', function(e) {
            let valor = e.target.value;

            // Permitir solo letras, números, espacios y guiones
            valor = valor.replace(/[^A-Z0-9À-ÿ\s-]/gi, '');

            // Convertir todo a mayúsculas
            valor = valor.toUpperCase();

            // Actualizar el valor del input
            e.target.value = valor;
        });
        document.getElementById('referencias').addEventListener('input', function(e) {
            let valor = e.target.value;

            // Permitir solo letras, números, espacios y guiones
            valor = valor.replace(/[^A-Z0-9À-ÿ\s-]/gi, '');

            // Convertir todo a mayúsculas
            valor = valor.toUpperCase();

            // Actualizar el valor del input
            e.target.value = valor;
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

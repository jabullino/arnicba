@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="card shadow">
            <div class="card-header text-white text-center bold-md bg-teal-900">
                FORMULARIO DE REGISTRO DE PRODUCTOS
            </div>

            <div class="card-body">
                <form action="{{ route('ProductoEscolar.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        {{-- CATEGORIA --}}
                        <div class="col-12 col-md-6 mb-2 form-group">
                            <label>Categoría</label>
                            <select name="categoria_id" id="categoria" required class="form-control">
                                <option value="">Seleccione</option>
                                @foreach ($categorias as $categoria)
                                    <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- DATOS BASICOS --}}
                        <div class="col-12 col-md-6 mb-2 form-group">
                            <label>Nombre</label>
                            <input type="text" name="nombre" maxlength="100" required class="form-control">
                        </div>

                        <div class="col-12 col-md-6 mb-2 form-group">
                            <label>Marca</label>
                            <input type="text" name="marca" maxlength="50" required class="form-control">
                        </div>

                        <div class="col-12 col-md-6 mb-2 form-group">
                            <label>Código</label>
                            <input type="text" name="codigo" maxlength="50" required class="form-control">
                        </div>

                        {{-- SELECTS GENERALES --}}
                        <div class="col-12 col-md-6 mb-2 form-group">
                            <label>Presentación</label>
                            <select name="presentacion_id" class="form-control">
                                @foreach ($presentaciones as $p)
                                    <option value="{{ $p->id }}">{{ $p->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-md-6 mb-2 form-group">
                            <label>Capacidad</label>
                            <select name="capacidad_id" class="form-control">
                                @foreach ($capacidades as $c)
                                    <option value="{{ $c->id }}">{{ $c->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-md-6 mb-2 form-group">
                            <label>Unidad</label>
                            <select name="unidad_id" class="form-control">
                                @foreach ($unidades as $u)
                                    <option value="{{ $u->id }}">{{ $u->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- CAMPOS CONDICIONALES --}}

                        <div class="col-12 col-md-6 mb-2 form-group">
                            <label>Color</label>
                            <select name="color_id" id="color" class="form-control">
                                @foreach ($colores as $color)
                                     <option value="0">Escoja un Color</option>
                                    <option value="{{ $color->id }}">{{ $color->nombre }}</option>
                                @endforeach
                            </select>
                        </div>


                        <div class="col-12 col-md-6 mb-2 form-group">
                            <label>Largo</label>
                            <input type="number" name="largo" id="largo" disabled class="form-control">
                        </div>

                        <div class="col-12 col-md-6 mb-2 form-group">
                            <label>Ancho</label>
                            <input type="number" name="ancho" id="ancho" disabled class="form-control">
                        </div>

                        {{-- BOTON --}}
                        <button type="submit" class="btn w-100 bg-teal-900 text-white hover:bg-green-900">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    {{-- SWEETALERT2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.getElementById('categoria').addEventListener('change', function() {
            let categoria = parseInt(this.value);

            let color = document.getElementById('color');
            let talla = document.getElementById('talla');
            let tallazapato = document.getElementById('tallazapato');
            let largo = document.getElementById('largo');
            let ancho = document.getElementById('ancho');

            // SOLO desactivar estos campos
            [talla, tallazapato, largo, ancho].forEach(el => {
                if (!el) return;
                el.disabled = true;
                el.value = '';
            });

            if (categoria === 1 || categoria === 2 || categoria === 3) return;

            if (categoria === 4) {
                color.disabled = false;
                largo.disabled = false;
                ancho.disabled = false;
            }

            if (categoria === 5) {
                color.disabled = false;
                talla.disabled = false;
            }

            if (categoria === 6) {
                color.disabled = false;
                tallazapato.disabled = false;
            }

            // CATEGORIA 7 → COLOR SIEMPRE ACTIVO
            if (categoria === 7) {
                color.disabled = false;
            }
        });
    </script>

    {{-- MENSAJES SWEETALERT --}}
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: "{{ session('success') }}",
                confirmButtonColor: '#0f766e'
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: "{{ session('error') }}",
                confirmButtonColor: '#991b1b'
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Errores de validación',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                confirmButtonColor: '#991b1b'
            });
        </script>
    @endif
    <script>
        // MAYÚSCULAS AUTOMÁTICAS
        document.addEventListener('DOMContentLoaded', function() {

            const nombre = document.querySelector('input[name="nombre"]');
            const marca = document.querySelector('input[name="marca"]');
            const codigo = document.querySelector('input[name="codigo"]');

            if (nombre) {
                nombre.addEventListener('input', function() {
                    this.value = this.value.toUpperCase();
                });
            }

            if (marca) {
                marca.addEventListener('input', function() {
                    this.value = this.value.toUpperCase();
                });
            }

            if (codigo) {
                codigo.addEventListener('input', function() {
                    this.value = this.value.toUpperCase();
                });
            }

            // SOLO NÚMEROS Y PUNTO DECIMAL
            function soloNumerosConDecimal(input) {
                if (!input) return;

                input.addEventListener('input', function() {
                    this.value = this.value
                        .replace(/[^0-9.]/g, '')
                        .replace(/(\..*)\./g, '$1');
                });
            }

            soloNumerosConDecimal(document.getElementById('largo'));
            soloNumerosConDecimal(document.getElementById('ancho'));
            soloNumerosConDecimal(document.getElementById('saldo'));

        });
    </script>
@endsection

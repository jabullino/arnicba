@extends('layouts.app')

@section('content')
<div class="container-fluid px-3 px-md-4">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">

            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 text-center text-md-start">Editar producto</h5>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger m-3">
                        <strong>Corrige los siguientes errores:</strong>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card-body">
                    <form action="{{ route('Producto.update', $producto->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- ================= DATOS GENERALES ================= --}}
                        <h6 class="mb-3 text-muted">Datos generales</h6>

                        <div class="row g-3">

                            <div class="col-12 col-md-6">
                                <label class="form-label">Nombre</label>
                                <input type="text" name="nombre" id="nombre"
                                    class="form-control"
                                    maxlength="30"
                                    value="{{ old('nombre', $producto->nombre) }}">
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label">Marca</label>
                                <input type="text" name="marca" id="marca"
                                    class="form-control"
                                    maxlength="50"
                                    value="{{ old('marca', $producto->marca) }}">
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label">Código</label>
                                <input type="text" name="codigo" id="codigo"
                                    class="form-control"
                                    maxlength="50"
                                    value="{{ old('codigo', $producto->codigo) }}">
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label">Categoría</label>
                                <select name="categoria_id" id="categoria_id" class="form-control">
                                    @foreach ($categorias as $categoria)
                                        <option value="{{ $categoria->id }}"
                                            {{ old('categoria_id', $producto->categoria_id) == $categoria->id ? 'selected' : '' }}>
                                            {{ $categoria->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                        <hr class="my-4">

                        {{-- ================= CONFIGURACIÓN ================= --}}
                        <h6 class="mb-3 text-muted">Configuración</h6>

                        <div class="row g-3">

                            <div class="col-12 col-md-4">
                                <label class="form-label">Presentación</label>
                                <select name="presentacion_id" class="form-control">
                                    @foreach ($presentaciones as $p)
                                        <option value="{{ $p->id }}"
                                            {{ old('presentacion_id', $producto->presentacion_id ?? '') == $p->id ? 'selected' : '' }}>
                                            {{ $p->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 col-md-4">
                                <label class="form-label">Capacidad</label>
                                <select name="capacidad_id" class="form-control">
                                    @foreach ($capacidades as $c)
                                        <option value="{{ $c->id }}"
                                            {{ old('capacidad_id', $producto->capacidad_id ?? '') == $c->id ? 'selected' : '' }}>
                                            {{ $c->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 col-md-4">
                                <label class="form-label">Unidad</label>
                                <select name="unidad_id" class="form-control">
                                    @foreach ($unidades as $u)
                                        <option value="{{ $u->id }}"
                                            {{ old('unidad_id', $producto->unidad_id ?? '') == $u->id ? 'selected' : '' }}>
                                            {{ $u->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                        {{-- ================= TELA ================= --}}
                        <div id="seccion-tela" style="display:none;">
                            <hr class="my-4">
                            <h6 class="text-muted">Datos de tela</h6>

                            <div class="row g-3">
                                <div class="col-12 col-md-4">
                                    <label>Color</label>
                                    <select name="color_id" class="form-control">
                                        <option value="">Seleccione color</option>
                                        @foreach ($colores as $color)
                                            <option value="{{ $color->id }}"
                                                {{ old('color_id', $producto->color_id ?? '') == $color->id ? 'selected' : '' }}>
                                                {{ $color->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-4">
                                    <label>Largo</label>
                                    <input type="number" step="0.01" name="largo"
                                        class="form-control"
                                        value="{{ old('largo', $producto->largo ?? '') }}">
                                </div>

                                <div class="col-12 col-md-4">
                                    <label>Ancho</label>
                                    <input type="number" step="0.01" name="ancho"
                                        class="form-control"
                                        value="{{ old('ancho', $producto->ancho ?? '') }}">
                                </div>
                            </div>
                        </div>

                        {{-- ================= VESTIMENTA ================= --}}
                        <div id="seccion-vestimenta" style="display:none;">
                            <hr class="my-4">
                            <h6 class="text-muted">Datos de vestimenta</h6>

                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label>Talla</label>
                                    <select name="talla_id" class="form-control">
                                        <option value="">Seleccione talla</option>
                                        @foreach ($tallas as $talla)
                                            <option value="{{ $talla->id }}"
                                                {{ old('talla_id', $producto->talla_id ?? '') == $talla->id ? 'selected' : '' }}>
                                                {{ $talla->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label>Color</label>
                                    <select name="color_id" class="form-control">
                                        <option value="">Seleccione color</option>
                                        @foreach ($colores as $color)
                                            <option value="{{ $color->id }}"
                                                {{ old('color_id', $producto->color_id ?? '') == $color->id ? 'selected' : '' }}>
                                                {{ $color->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- ================= ZAPATOS ================= --}}
                        <div id="seccion-zapatos" style="display:none;">
                            <hr class="my-4">
                            <h6 class="text-muted">Datos de zapatos</h6>

                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label>Talla Zapato</label>
                                    <select name="tallazapato_id" class="form-control">
                                        <option value="">Seleccione talla</option>
                                        @foreach ($tallazapatos as $t)
                                            <option value="{{ $t->id }}"
                                                {{ old('tallazapato_id', $producto->tallazapato_id ?? '') == $t->id ? 'selected' : '' }}>
                                                {{ $t->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label>Color</label>
                                    <select name="color_id" class="form-control">
                                        <option value="">Seleccione color</option>
                                        @foreach ($colores as $color)
                                            <option value="{{ $color->id }}"
                                                {{ old('color_id', $producto->color_id ?? '') == $color->id ? 'selected' : '' }}>
                                                {{ $color->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex flex-column flex-md-row justify-content-end gap-2">
                            <a href="{{ route('Producto.index') }}" class="btn btn-secondary w-100 w-md-auto">Cancelar</a>
                            <button class="btn btn-primary w-100 w-md-auto">Actualizar producto</button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {

    // ===== MAYÚSCULAS AUTOMÁTICAS =====
    ['nombre', 'marca', 'codigo'].forEach(id => {
        const input = document.getElementById(id);
        if (input) {
            input.addEventListener('input', function() {
                this.value = this.value.toUpperCase();
            });
        }
    });

    // ===== SECCIONES DINÁMICAS =====
    function toggleSection(sectionId, enabled) {
        const section = document.getElementById(sectionId);
        section.style.display = enabled ? 'block' : 'none';

        section.querySelectorAll('input, select').forEach(el => {
            el.disabled = !enabled;
        });
    }

    function mostrarSecciones() {
        let cat = document.getElementById('categoria_id').value;

        toggleSection('seccion-tela', cat == 4);
        toggleSection('seccion-vestimenta', cat == 5);
        toggleSection('seccion-zapatos', cat == 6);
    }

    document.getElementById('categoria_id')
        .addEventListener('change', mostrarSecciones);

    mostrarSecciones();
});
</script>
@endsection
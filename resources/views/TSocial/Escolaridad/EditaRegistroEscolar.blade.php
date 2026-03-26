@extends('layouts.app')

@section('content')
<div id='principal' class="mt-5" style="max-width: 900px; margin: 0 auto;">
<div class="content-wrapper">

    <!-- Encabezado -->
    <section class="content-header">
        <div class="container-fluid text-center py-2" style="background-color: #343a40;">
            <h4 class="fw-bold text-white mb-0">✏️ Editar Registro Escolar</h4>
        </div>
    </section>

    <!-- Contenido principal -->
    <section class="content">
        <div class="container py-3">
            <div class="card shadow-lg rounded-3">
                <div class="card-body">

                    <form id="formEditRegistro" action="{{ route('escolaridad.update', $registro->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Alumno -->
                        <div class="row mb-3 justify-content-center">
                            <div class="col-12 col-md-6">
                                <label class="form-label">Alumno:</label>
                                <input type="text" class="form-control"
                                       value="{{ $residente->nombre }} {{ $residente->apellido }}" disabled>
                            </div>
                        </div>

                        <!-- Unidad Educativa -->
                        <div class="row mb-3 justify-content-center">
                            <div class="col-12 col-md-6">
                                <label class="form-label">Unidad Educativa:</label>
                                <select name="ue_id" class="form-select" required>
                                    @foreach($unidades as $u)
                                        <option value="{{ $u->id }}" {{ $u->id == $registro->ue_id ? 'selected' : '' }}>
                                            {{ $u->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Curso -->
                        <div class="row mb-3 justify-content-center">
                            <div class="col-12 col-md-6">
                                <label class="form-label">Curso:</label>
                                <select name="curso_id" class="form-select" required>
                                    @foreach($cursos as $c)
                                        <option value="{{ $c->id }}" {{ $c->id == $registro->curso_id ? 'selected' : '' }}>
                                            {{ $c->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Grado -->
                        <div class="row mb-3 justify-content-center">
                            <div class="col-12 col-md-6">
                                <label class="form-label">Grado:</label>
                                <select name="grado_id" class="form-select" required>
                                    @foreach($grados as $g)
                                        <option value="{{ $g->id }}" {{ $g->id == $registro->grado_id ? 'selected' : '' }}>
                                            {{ $g->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="row justify-content-center">
                            <div class="col-12 col-md-6 text-center">
                                <button type="submit" class="btn btn-success me-2">
                                    <i class="bi bi-save"></i> Guardar cambios
                                </button>
                                <a href="{{ route('escolaridad.index') }}" class="btn btn-secondary">
                                    Cancelar
                                </a>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </section>

</div>
</div>
@endsection


@section('css')

<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

<style>

/* 🔥 FONDO GLOBAL */
body, .wrapper, .content-wrapper {
    background-color: #343a40 !important;
    color: #f8f9fa !important;
}

/* 🔥 NAVBAR */
.main-header {
    background-color: #212529 !important;
    border-bottom: 1px solid #495057 !important;
}

.main-header .nav-link {
    color: #fff !important;
}

/* 🔥 CARD */
.card {
    background-color: #454d55 !important;
    color: #fff !important;
    border: 1px solid #495057 !important;
}

/* 🔥 INPUTS */
.form-control, .form-select {
    background-color: #3b4045 !important;
    color: #fff !important;
    border: 1px solid #555 !important;
}

/* 🔥 LABELS */
label {
    color: #f8f9fa !important;
}

/* 🔥 BOTONES */
.btn-success {
    background-color: #198754 !important;
}

.btn-secondary {
    background-color: #6c757d !important;
}

/* 🔥 SWEET ALERT */
.swal2-popup {
    background-color: #2f353a !important;
    color: #fff !important;
}

</style>

@endsection


@section('js')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {

    const form = document.getElementById('formEditRegistro');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = new FormData(form);

        try {
            const resp = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            });

            const json = await resp.json();

            if (json.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Registro actualizado',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = "{{ route('escolaridad.index') }}";
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: json.message || 'No se pudo actualizar el registro'
                });
            }

        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ocurrió un error'
            });
        }
    });

});
</script>

@endsection

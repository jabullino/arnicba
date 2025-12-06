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
            <div class="card shadow-lg rounded-3" style="background-color: #454d55; color: #f8f9fa;">
                <div class="card-body">
                    <form id="formEditRegistro" action="{{ route('escolaridad.update', $registro->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Alumno -->
                        <div class="row mb-3 justify-content-center">
                            <div class="col-12 col-md-6">
                                <label for="residente" class="form-label">Alumno:</label>
                                <input type="text" id="residente" class="form-control bg-dark text-light border-secondary" 
                                       value="{{ $residente->nombre }} {{ $residente->apellido }}" disabled>
                            </div>
                        </div>

                        <!-- Unidad Educativa -->
                        <div class="row mb-3 justify-content-center">
                            <div class="col-12 col-md-6">
                                <label for="unidad" class="form-label">Unidad Educativa:</label>
                                <select name="ue_id" id="unidad" class="form-select bg-dark text-light border-secondary" required>
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
                                <label for="curso" class="form-label">Curso:</label>
                                <select name="curso_id" id="curso" class="form-select bg-dark text-light border-secondary" required>
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
                                <label for="grado" class="form-label">Grado:</label>
                                <select name="grado_id" id="grado" class="form-select bg-dark text-light border-secondary" required>
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
                            <div class="col-12 col-md-6 text-center bg-teal-900">
                                <button type="submit" class=" bg-teal-900 btn btn-success mb-2">
                                    <i class="bi bi-save bg-teal-900"></i> Guardar cambios
                                </button>
                                <a href="{{ route('escolaridad.index') }}" class="btn btn-secondary mb-2">
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
</div><!-- fin div principal -->
@endsection

@section('css')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
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
            console.error(error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ocurrió un error al actualizar el registro'
            });
        }
    });
});
</script>
@endsection

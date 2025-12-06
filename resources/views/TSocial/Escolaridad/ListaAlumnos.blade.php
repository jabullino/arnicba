@extends('layouts.app')

@section('content')
<div id='principal' class='mt-5 mx:auto!' >
<div class="content-wrapper">
    <!-- Encabezado -->
    <section class="content-header">
        <div class="container-fluid text-center py-2" style="background-color: #343a40;">
            <h4 class="fw-bold text-white mb-0">📚 Lista de Alumnos por Gestión</h4>
        </div>
    </section>

    <!-- Contenido principal -->
    <section class="content">
        <div class="container-fluid py-3">
            <div class="card shadow-lg rounded-3" style="background-color: #454d55; color: #f8f9fa;">
                <div class="card-body">
                    <!-- Select de Gestión -->
                    <div class="row mb-3">
                        <div class="col-md-6 mx-auto">
                            <label for="gestionSelect" class="form-label">Seleccione una Gestión:</label>
                            <select id="gestionSelect" class="form-select bg-dark text-light border-secondary">
                                <option value="">-- Seleccione --</option>
                                @foreach($gestiones as $gestion)
                                    <option value="{{ $gestion->id }}">{{ $gestion->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Tabla centrada -->
                    <div class="table-wrapper" style="max-width: 900px; margin: 0 auto;">
                        <div class="table-responsive">
                            <table id="tablaAlumnos" class="table table-dark table-striped table-bordered align-middle text-center">
                                <thead class="table-secondary text-dark">
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Apellido</th>
                                        <th>Unidad Educativa</th>
                                        <th>Curso</th>
                                        <th>Grado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
</div>
</div><!-- fin div principal-->
@endsection

@section('css')
<style>
.table-wrapper {
    max-width: 900px;
    margin: 0 auto; /* centra el contenedor */
}
</style>

<link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {

    const selectGestion = document.getElementById('gestionSelect');

    // Inicializamos DataTable
    const tabla = $('#tablaAlumnos').DataTable({
        responsive: true,
        autoWidth: false,
        language: { url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json' },
        columns: [
            { data: 'nombre' },
            { data: 'apellido' },
            { data: 'unidad_educativa' },
            { data: 'curso' },
            { data: 'grado' },
            { data: 'acciones', orderable: false, searchable: false }
        ]
    });

    // Función para cargar alumnos
    async function cargarAlumnos(gestionId) {
        if (!gestionId) {
            tabla.clear().draw();
            return;
        }

        try {
            tabla.clear().draw();

            const url = `{{ route('escolaridad.alumnos') }}?gestion_id=${encodeURIComponent(gestionId)}`;
            const resp = await fetch(url, { headers: { 'Accept': 'application/json' } });

            if (!resp.ok) throw new Error('Error en la respuesta del servidor');

            const json = await resp.json();

            if (json.error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error al obtener los datos',
                    text: json.error
                });
                return;
            }

            if (Array.isArray(json.data)) {
                const filas = json.data.map(item => ({
                    nombre: item.nombre ?? '',
                    apellido: item.apellido ?? '',
                    unidad_educativa: item.unidad_educativa ?? '',
                    curso: item.curso ?? '',
                    grado: item.grado ?? '',
                    acciones: `
                        <button class="btn btn-sm btn-primary me-1" onclick="editarAlumno(${item.id})">
                            <i class="bi bi-pencil"></i> Editar
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="eliminarAlumno(${item.id})">
                            <i class="bi bi-trash"></i> Eliminar
                        </button>
                    `
                }));
                tabla.rows.add(filas).draw();
            }

        } catch (error) {
            console.error(error);
            Swal.fire({
                icon: 'error',
                title: 'Error al obtener los datos',
                text: 'Hubo un problema al cargar los alumnos. Verifique la consola del navegador.'
            });
        }
    }

    // Evento change del select
    selectGestion.addEventListener('change', () => {
        cargarAlumnos(selectGestion.value);
    });

    // Funciones globales de Editar y Eliminar
    window.editarAlumno = function(id) {
        window.location.href = `/TSocial/escolaridad/${id}/edit`;
    }

    window.eliminarAlumno = async function(id) {
        const result = await Swal.fire({
            icon: 'warning',
            title: '¿Está seguro?',
            text: 'Se eliminará este registro.',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        });

        if (!result.isConfirmed) return;

        try {
            const resp = await fetch(`/TSocial/escolaridad/${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            if (!resp.ok) throw new Error('No se pudo eliminar');

            // Recargar tabla sin recargar página
            cargarAlumnos(selectGestion.value);

            Swal.fire({
                icon: 'success',
                title: 'Registro eliminado',
                timer: 1500,
                showConfirmButton: false
            });

        } catch (e) {
            console.error(e);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo eliminar el registro.'
            });
        }
    }

});
</script>
@endsection

@extends('layouts.app')

@section('content')
<div id='principal' class='mt-5'>
<div class="content-wrapper">

    <!-- Encabezado -->
    <section class="content-header">
        <div class="container-fluid text-center py-2" style="background-color: #343a40;">
            <h4 class="fw-bold text-white mb-0">📚 Lista de Alumnos por Gestión</h4>
        </div>
    </section>

    <!-- Contenido -->
    <section class="content">
        <div class="container-fluid py-3">

            <div class="card shadow-lg rounded-3">
                <div class="card-body">

                    <!-- Select -->
                    <div class="row mb-3">
                        <div class="col-md-6 mx-auto">
                            <label>Seleccione una Gestión:</label>
                            <select id="gestionSelect" class="form-select">
                                <option value="">-- Seleccione --</option>
                                @foreach($gestiones as $gestion)
                                    <option value="{{ $gestion->id }}">{{ $gestion->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Tabla -->
                    <div class="table-responsive">
                        <table id="tablaAlumnos" class="table table-dark table-striped table-bordered text-center">
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
    </section>

</div>
</div>
@endsection


@section('css')

<style>

/* 🔥 FONDO GLOBAL */
body, .wrapper, .content-wrapper {
    background-color: #343a40 !important;
    color: #f8f9fa !important;
}

/* 🔥 NAVBAR */
.main-header {
    background-color: #212529 !important;
}
.main-header .nav-link {
    color: #fff !important;
}

/* 🔥 CARD */
.card {
    background-color: #454d55 !important;
    color: #fff !important;
}

/* 🔥 INPUTS */
.form-control, .form-select {
    background-color: #3b4045 !important;
    color: #fff !important;
}

/* 🔥 BOTONES */
#tablaAlumnos .btn {
    width: auto !important;
    display: inline-block !important;
    margin: 2px;
}

/* 🔥 EVITA SALTO */
#tablaAlumnos td {
    white-space: nowrap;
}

/* 🔥 ⭐ SOLUCIÓN REAL DATATABLES ⭐ */
table.dataTable {
    width: auto !important;
    margin-left: auto !important;
    margin-right: auto !important;
}

/* 🔥 SWEET ALERT */
.swal2-popup {
    background-color: #2f353a !important;
    color: #fff !important;
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

    const tabla = $('#tablaAlumnos').DataTable({
        responsive: true,
        autoWidth: false,
        language: { url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json' }
    });

    document.getElementById('gestionSelect').addEventListener('change', async function () {

        const gestionId = this.value;

        if (!gestionId) {
            tabla.clear().draw();
            return;
        }

        const resp = await fetch(`{{ route('escolaridad.alumnos') }}?gestion_id=${gestionId}`);
        const json = await resp.json();

        const filas = json.data.map(item => ({
            0: item.nombre,
            1: item.apellido,
            2: item.unidad_educativa,
            3: item.curso,
            4: item.grado,
            5: `
                <button class="btn btn-sm btn-primary" onclick="editarAlumno(${item.id})">Editar</button>
                <button class="btn btn-sm btn-danger" onclick="eliminarAlumno(${item.id})">Eliminar</button>
            `
        }));

        tabla.clear().rows.add(filas).draw();
    });

    window.editarAlumno = id => {
        window.location.href = `/TSocial/escolaridad/${id}/edit`;
    }

    window.eliminarAlumno = async id => {
        const confirm = await Swal.fire({
            title: '¿Eliminar?',
            showCancelButton: true
        });

        if (!confirm.isConfirmed) return;

        await fetch(`/TSocial/escolaridad/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        });

        location.reload();
    }

});
</script>

@endsection

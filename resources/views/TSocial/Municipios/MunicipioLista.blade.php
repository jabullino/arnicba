@extends('layouts.app')

@section('content')

<div class="p-4">
    <div class="card mx-auto w-full max-w-3xl">
        <div class="card-header text-center bg-neutral-500 text-white">
            FORMULARIO DE EDICION DE MUNICIPIOS
        </div>

        <div class="card-body bg-neutral-500 p-4">
            <form action="" method="POST">
                @csrf
                <div class="form-group mb-2">
                    <label for="ciudad_id" class="block mb-1">Ciudad</label>
                    <select name="ciudad_id" id="ciudad_id" class="form-control w-full">
                        <option value="">Escoja una Ciudad</option>
                        @foreach ($ciudades as $ciudad)
                            <option value="{{ $ciudad->id }}" {{ old('ciudad_id') == $ciudad->id ? 'selected' : '' }}>
                                {{ $ciudad->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>

            <div class="table-responsive mt-4">
                <table class="table table-striped table-dark w-full" id="tablaMunicipios">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Aquí se cargarán los municipios -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: '¡Éxito!',
        text: '{{ session("success") }}',
        confirmButtonColor: '#3085d6',
    });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: '¡Error!',
        text: '{{ session("error") }}',
        confirmButtonColor: '#d33',
    });
</script>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {

    const selectCiudad = document.getElementById('ciudad_id');
    const tbody = document.querySelector('#tablaMunicipios tbody');

    selectCiudad.addEventListener('change', function() {
        const ciudadId = this.value;
        if(!ciudadId) {
            tbody.innerHTML = '';
            return;
        }

        fetch(`/municipios/${ciudadId}`)
            .then(response => response.json())
            .then(data => {
                let html = '';
                data.forEach(municipio => {
                    const editUrl = `/TSocial/Municipios/${municipio.id}/edit`;
                    const deleteUrl = `/TSocial/Municipios/${municipio.id}`;

                    html += `<tr>
                        <td>${municipio.id}</td>
                        <td>${municipio.nombre}</td>
                        <td style="min-width: 160px;">
                            <div class="d-flex flex-wrap gap-2">
                                <a href="${editUrl}" class="btn btn-sm btn-primary flex-fill">Editar</a>
                                <button class="btn btn-sm btn-danger btnEliminar flex-fill" data-id="${municipio.id}">Eliminar</button>
                            </div>
                        </td>
                    </tr>`;
                });
                tbody.innerHTML = html;

                // Agregar evento a botones eliminar
                document.querySelectorAll('.btnEliminar').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const id = this.getAttribute('data-id');
                        Swal.fire({
                            title: '¿Está seguro?',
                            text: "¡Esta acción no se puede deshacer!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Sí, eliminar',
                            cancelButtonText: 'Cancelar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                fetch(`/TSocial/Municipios/${id}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json'
                                    }
                                })
                                .then(res => res.json())
                                .then(res => {
                                    if(res.success){
                                        Swal.fire('Eliminado!', res.message, 'success');
                                        // Quitar la fila de la tabla
                                        this.closest('tr').remove();
                                    } else {
                                        Swal.fire('Error', res.message, 'error');
                                    }
                                });
                            }
                        });
                    });
                });

            });
    });

});
</script>
@endsection

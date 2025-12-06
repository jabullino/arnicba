@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Lista de Derivaciones</h3>
        <a href="{{ route('derivaciones.create') }}" class="btn btn-success bg-teal-900">
            <i class="fas fa-plus"></i> Nueva Derivación
        </a>
    </div>

    <style>
        /* Hacer que el borde superior del thead sea igual al de las demás celdas */
        .table thead th {
            border-top: 1px solid #dee2e6 !important;
        }
    </style>

    <table class="table table-striped table-bordered align-middle">
        <thead class="table-dark">
            <tr class="text-center">
                <th>#</th>
                <th>Residente</th>
                <th>Fecha</th>
                <th>N° Juzgado</th>
                <th>N° Documento</th>
                <th>Nombre del Juez</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @php $num=1; @endphp
            @forelse($derivaciones as $derivacion)
                <tr class="text-center">
                    <td>{{ $num++ }}</td>
                    <td>{{ $derivacion->residente->nombre }} {{ $derivacion->residente->apellido }}</td>
                    <td>{{ \Carbon\Carbon::parse($derivacion->fecha)->format('d-m-Y') }}</td>
                    <td>{{ $derivacion->numjuzgado }}</td>
                    <td>{{ $derivacion->numdoc }}</td>
                    <td>{{ $derivacion->nomjuez }}</td>
                    <td>
                        <a href="{{ route('derivaciones.show', $derivacion->id) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('derivaciones.edit', $derivacion->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('derivaciones.destroy', $derivacion->id) }}" method="POST" class="d-inline delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-danger btn-sm btn-delete">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">No hay derivaciones registradas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const deleteButtons = document.querySelectorAll('.btn-delete');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function () {
            const form = this.closest('form');

            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});
</script>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: '¡Éxito!',
        text: '{{ session('success') }}',
        confirmButtonColor: '#3085d6',
    });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: '{{ session('error') }}',
        confirmButtonColor: '#d33',
    });
</script>
@endif
@endsection

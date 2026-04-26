@extends('layouts.app')

@section('content')
<div class="container mt-4">
      <div class='text-center text-white'>
                <h2>Lista de Residentes</h2>
      </div>
    <div class="d-flex justify-end gap-2 align-items-center mb-3">

        <a href="{{ route('residentes.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Nuevo Residente
        </a>
         <a href="{{ route('reporte.residentes') }}" class="btn btn-primary" target="_blank">
    Generar Reporte PDF
    </a>
    </div>
     


    <style>
        /* Corrige el grosor desigual del borde superior del encabezado */
        .table thead th {
            border-top: 1px solid #dee2e6 !important;
        }
    </style>

    <table class="table table-striped table-bordered align-middle">
        <thead class="table-dark">
            <tr class="text-center">
                <th class='text-center'>Num</th>
                <th class='text-center'>ID</th>
                <th class='text-center'>Nombre</th>
                <th class='text-center'>Apellido</th>
                <th class='text-center'>Foto</th>
                <th class='text-center'>Acciones</th>
            </tr>
        </thead>
        <tbody>
            
            @forelse ($residentes as $residente)
                <tr class="text-center">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $residente->id }}</td>
                    <td>{{ $residente->nombre }}</td>
                    <td>{{ $residente->apellido }}</td>
                    <td>
    @if($residente->foto)
        <img src="{{ asset('storage/fotos_residentes/' . basename($residente->foto)) }}" 
             alt="Foto de {{ $residente->nombre }}" 
             class="img-thumbnail"
             style="width: 60px; height: 60px; object-fit: cover; cursor:pointer;"
             onclick="mostrarImagen(this.src)">
    @endif
</td>
                    <td>
                        <a href="{{ route('residentes.show', $residente->id) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('residentes.edit', $residente->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('residentes.destroy', $residente->id) }}" method="POST" class="d-inline delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">No hay residentes registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
<div id="modalImagen" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; 
     background:rgba(0,0,0,0.8); justify-content:center; align-items:center; z-index:9999;">
    
    <img id="imagenGrande" style="max-width:90%; max-height:90%; border-radius:10px;">
</div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const deleteForms = document.querySelectorAll('.delete-form');

    deleteForms.forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esta acción!",
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
<script>
function mostrarImagen(src) {
    document.getElementById('imagenGrande').src = src;
    document.getElementById('modalImagen').style.display = 'flex';
}

document.getElementById('modalImagen').addEventListener('click', function() {
    this.style.display = 'none';
});
</script>
@endsection

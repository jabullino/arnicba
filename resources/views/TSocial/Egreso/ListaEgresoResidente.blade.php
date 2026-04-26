@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-3">
        <h3 class="mb-2 mb-md-0">Lista de Egresos</h3>

        <div class="d-flex gap-2">
            <a href="{{ route('egresoresidente.index') }}" class="btn btn-success bg-teal-900">
                <i class="fas fa-plus"></i> Nuevo Egreso
            </a>

            <button onclick="imprimirTabla()" class="btn btn-primary">
                <i class="fas fa-print"></i> Imprimir
            </button>
        </div>
    </div>

    <style>
        .table thead th {
            border-top: 1px solid #dee2e6 !important;
        }

        /* 🔥 Responsive tabla */
        @media (max-width: 768px) {
            table {
                font-size: 12px;
            }
        }
    </style>

    <!-- 🔽 SOLO ESTA PARTE SE IMPRIME -->
    <div id="area-imprimir" class="table-responsive">
        <table class="table table-striped table-bordered align-middle">
            <thead class="table-dark">
                <tr class="text-center">
                    <th>#</th>
                    <th>Residente</th>
                    <th>Fecha</th>
                    <th>N° Juzgado</th>
                    <th>Municipio</th>
                    <th>Motivo Egreso</th>
                    <th>Destino</th>
                    <th>N° Documento</th>
                    <th>Nombre del Juez</th>
                    <th class="no-print">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @php $num=1; @endphp
                @forelse($egresos as $egreso)
                    <tr class="text-center">
                        <td>{{ $num++ }}</td>
                        <td>{{ $egreso->residente->nombre }} {{ $egreso->residente->apellido }}</td>
                        <td>{{ \Carbon\Carbon::parse($egreso->fecha)->format('d-m-Y') }}</td>
                        <td>{{ $egreso->numjuzgado }}</td>

                        @php
                            $mun = $municipio->devuelveNombre($egreso->municipio_id);
                            $mot = $motivo->devuelveNombre($egreso->motivo_id);
                        @endphp

                        <td>{{ $mun }}</td>
                        <td>{{ $mot }}</td>
                        <td>{{ $egreso->destino }}</td>
                        <td>{{ $egreso->numdoc }}</td>
                        <td>{{ $egreso->nomjuez }}</td>

                        <td class="no-print">
                            <div class="d-flex justify-content-center gap-1 flex-wrap">
                                <a href="{{ route('egresoresidente.show', $egreso->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('egresoresidente.edit', $egreso->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('egresoresidente.destroy', $egreso->id) }}" method="POST" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm btn-delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted">No hay egresos registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
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

<!-- 🔥 FUNCIÓN IMPRIMIR -->
<script>
function imprimirTabla() {
    let contenido = document.getElementById('area-imprimir').innerHTML;

    let ventana = window.open('', '', 'height=800,width=1000');

    ventana.document.write('<html><head><title>Lista de Egresos</title>');

    ventana.document.write(`
        <style>
            body { font-family: Arial; padding: 20px; }
            h3 { text-align: center; }
            table { width: 100%; border-collapse: collapse; }
            th, td { border: 1px solid #000; padding: 8px; text-align: center; }
            th { background-color: #333; color: #fff; }
            .no-print { display: none; }
        </style>
    `);

    ventana.document.write('</head><body>');
    ventana.document.write('<h3>Lista de Egresos</h3>');
    ventana.document.write(contenido);
    ventana.document.write('</body></html>');

    ventana.document.close();
    ventana.print();
}
</script>

{{-- ✅ MENSAJE SUCCESS --}}
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

{{-- ❌ MENSAJE ERROR --}}
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

{{-- ⚠️ ERRORES DE VALIDACIÓN --}}
@if ($errors->any())
<script>
Swal.fire({
    icon: 'warning',
    title: 'Errores de validación',
    html: `{!! implode('<br>', $errors->all()) !!}`,
    confirmButtonColor: '#f0ad4e',
});
</script>
@endif

@endsection
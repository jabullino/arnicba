@extends('layouts.app')

@section('content')
    <div class="container-fluid px-2 px-md-4">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-10">

                <div class="card shadow-lg border-0">

                    <div class="card-header text-white text-center py-3" style="background-color:#134e4a;">
                        <h5 class="mb-0">SELECCIONAR INGRESO A EDITAR</h5>
                    </div>

                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-hover table-bordered align-middle text-center">

                                <thead style="background-color:#134e4a; color:white;">
                                    <tr>
                                        <th>ID</th>
                                        <th>Fecha</th>
                                        <th>Documento</th>
                                        <th>Origen</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($ingresos as $ingreso)
                                        <tr>
                                            <td>{{ $ingreso->id }}</td>

                                            <td>
                                                {{ \Carbon\Carbon::parse($ingreso->fecha)->format('d/m/Y') }}
                                            </td>

                                            <td>
                                                @if (!empty($ingreso->factura))
                                                    <span class="badge bg-primary">
                                                        Factura: {{ $ingreso->factura }}
                                                    </span>
                                                @elseif(!empty($ingreso->recibo))
                                                    <span class="badge bg-success">
                                                        Recibo: {{ $ingreso->recibo }}
                                                    </span>
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <td>
                                                {{ $ingreso->origen->nombre ?? '-' }}
                                            </td>

                                            <td>
                                                <div class="d-flex justify-content-center align-items-center gap-2">

                                                    <a href="{{ route('IngresoEscolar.edit', $ingreso->id) }}"
                                                        class="btn btn-warning btn-sm">
                                                        Editar
                                                    </a>

                                                    <form id="form-eliminar-{{ $ingreso->id }}"
                                                        action="{{ route('IngresoEscolar.destroy', $ingreso->id) }}"
                                                        method="POST" class="m-0">

                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="button" class="btn btn-danger btn-sm"
                                                            onclick="confirmarEliminacion({{ $ingreso->id }})">
                                                            Eliminar
                                                        </button>
                                                    </form>

                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5">
                                                No hay ingresos registrados.
                                            </td>
                                        </tr>
                                    @endforelse
                                    
                                </tbody>

                            </table>
                        </div>

                        {{-- PAGINACIÓN --}}
                        <div class="d-flex justify-content-center mt-3">
                            {{ $ingresos->links() }}
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection


{{-- ================= SWEET ALERT ================= --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- MENSAJE SUCCESS --}}
@if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Éxito',
            text: '{{ session('success') }}',
            confirmButtonColor: '#134e4a'
        });
    </script>
@endif

{{-- MENSAJE ERROR --}}
@if (session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ session('error') }}',
            confirmButtonColor: '#d33'
        });
    </script>
@endif

<script>
    function confirmarEliminacion(id) {

        Swal.fire({
            title: '¿Está seguro?',
            text: "Esta acción eliminará el ingreso y revertirá el stock.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-eliminar-' + id).submit();
            }
        });

    }
</script>

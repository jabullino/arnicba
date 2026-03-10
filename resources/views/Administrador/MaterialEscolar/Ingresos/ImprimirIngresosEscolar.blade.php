@extends('layouts.app')

@section('content')
    <div class="container-fluid px-2 px-md-4">

        <div class="row justify-content-center">
            <div class="col-12 col-xl-10">

                <div class="card shadow-lg border-0">

                    <div class="card-header text-white text-center py-3" style="background-color:#134e4a;">
                        <h5 class="mb-0">LISTADO DE INGRESOS</h5>
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
                                        <th>Imprimir</th>
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

                                            {{-- ✅ BOTÓN CORRECTAMENTE DENTRO DE TD --}}
                                            <td>
                                                <a
                                                    href="{{ route('escolar.impresion.ingreso.flujo', ['id' => $ingreso->id]) }}">
                                                    <button class="btn btn-primary">
                                                        Imprimir tarjetas Kardex
                                                    </button>
                                                </a>
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

{{-- CONFIRMACIÓN DE IMPRESIÓN --}}
@if (session('confirmar_impresion'))
    <script>
        Swal.fire({
            title: 'Confirmar impresión',
            text: '{{ session('confirmar_impresion.mensaje') }}',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, imprimir',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href =
                    "{{ route('imprimirIngresos', [
                        'id' => session('confirmar_impresion.ingreso_id'),
                        'confirmado' => 1,
                    ]) }}";
            }
        });
    </script>
@endif


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

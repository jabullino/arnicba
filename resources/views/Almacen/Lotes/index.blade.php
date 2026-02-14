@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <div class="card shadow">
            <div class="card-header text-white text-center" style="background-color:#134e4a; overflow: hidden;">
                <strong>LISTA DE LOTES</strong>

                <a href="{{ route('Lote.create') }}" class="btn btn-default btn-sm pull-right">
                    + Nuevo
                </a>
            </div>

            <div class="card-body table-responsive">

                <table class="table table-bordered table-hover text-center">
                    <thead style="background-color:#134e4a; color:white;">
                        <tr>
                            <th>ID</th>
                            <th>Producto</th>
                            <th>Código</th>
                            <th>Fecha Registro</th>
                             <th>Fec. Venc.</th>
                            <th>Cantidad</th>
                            <th>Saldo</th>
                            <th>Precio</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lotes as $lote)
                            <tr>
                                <td>{{ $lote->id }}</td>
                                <td>
                                    {{ $lote->producto_codigo }}
                                    {{ $lote->producto_nombre }}
                                    {{ $lote->producto_marca }}
                                </td>
                                <td>{{ $lote->codigo }}</td>
                                <td>{{ \Carbon\Carbon::parse($lote->created_at)->format('d/m/Y H:i') }}</td>
                                <td>{{ $lote->fec_venc ? \Carbon\Carbon::parse($lote->fec_venc)->format('d/m/Y') : '-' }}</td>
                                <td>{{ $lote->cantidad }}</td>
                                <td>{{ $lote->saldo }}</td>
                                <td>{{ $lote->precio }}</td>
                                <td>
                                    <a href="{{ route('Lote.edit', $lote->id) }}" class="btn btn-sm text-white"
                                        style="background-color:#134e4a;">
                                        Editar
                                    </a>

                                    <button onclick="eliminar({{ $lote->id }})" class="btn btn-sm btn-danger">
                                        Eliminar
                                    </button>

                                    <form id="delete-{{ $lote->id }}" action="{{ route('Lote.destroy', $lote->id) }}"
                                        method="POST" style="display:none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{ $lotes->links() }}

            </div>
        </div>

    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function eliminar(id) {
            Swal.fire({
                title: '¿Eliminar lote?',
                text: "Esta acción no se puede revertir",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#134e4a',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-' + id).submit();
                }
            });
        }
    </script>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Correcto',
                text: "{{ session('success') }}",
                confirmButtonColor: '#134e4a'
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: "{{ session('error') }}",
                confirmButtonColor: '#134e4a'
            });
        </script>
    @endif
@endsection

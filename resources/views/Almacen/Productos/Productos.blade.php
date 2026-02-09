@extends('layouts.app')

@section('content')

<div class="card shadow">
    <div class="card-header text-center bold">
        LISTADO DE PRODUCTOS
    </div>
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    <div class="card-body">
        <div class="container">

            <h3 class="mb-4 text-center bold">Lista de Productos</h3>

            @if ($productos->count() == 0)
                <div class="alert alert-warning text-center">
                    No hay productos para mostrar
                </div>
            @else
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Producto</th>
                            <th class="text-center">Código</th>
                            <th class="text-center">Saldo</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($productos as $index => $producto)
                            @php
                                $datos = $prod->obtenerNombreProducto(
                                    $producto->id,
                                    $producto->categoria_id
                                );
                            @endphp
                            <tr>
                                {{-- Numeración real con paginación --}}
                                <td class="text-center">
                                    {{ $productos->firstItem() + $index }}
                                </td>

                                <td>{{ $datos['nombre'] }}</td>

                                <td class="text-center">{{ $producto->codigo }}</td>

                                <td class="text-right">
                                    ${{ number_format($datos['saldo'], 2) }}
                                </td>

                                <td class="text-center">
                                    {{-- Editar --}}
                                    <a href="{{ route('Producto.edit', $producto->id) }}"
                                       class="btn btn-warning btn-sm">
                                        ✏️ Editar
                                    </a>

                                    {{-- Eliminar --}}
                                    <form action="{{ route('Producto.destroy', $producto->id) }}"
                                          method="POST"
                                          class="d-inline form-eliminar-producto">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-danger btn-sm">
                                            🗑️ Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Paginación --}}
                <div class="d-flex justify-content-center mt-3">
                    {{ $productos->links() }}
                </div>
            @endif

        </div>
    </div>
</div>

{{-- SweetAlert2 para eliminar --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const forms = document.querySelectorAll('.form-eliminar-producto');

    forms.forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            Swal.fire({
                title: '¿Eliminar producto?',
                text: 'Esta acción no se puede deshacer',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
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

@endsection
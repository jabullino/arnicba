@extends('layouts.app')

@section('page_header')
    <h1>Gastos Caja Chica</h1>
@stop

@section('page_content')
    {{-- SweetAlert --}}
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: "{{ session('success') }}",
                confirmButtonText: 'Aceptar'
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: '¡Error!',
                text: "{{ session('error') }}",
                confirmButtonText: 'Aceptar'
            });
        </script>
    @endif

    <table class="table-gastos">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Documento</th>
                <th>Cuenta</th>
                <th>Subcuenta</th>
                <th>Importe</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($gastos as $gasto)
                <tr>
                    <td data-label="Fecha">{{ \Carbon\Carbon::parse($gasto->fecha_doc)->format('d-m-Y') }}</td>
                    <td data-label="Documento">{{ $gasto->tipo_documento }}: {{ $gasto->documentos }}</td>
                    <td data-label="Cuenta">{{ $gasto->cuenta_nombre }}</td>
                    <td data-label="Subcuenta">{{ $gasto->subcuenta_nombre }}</td>
                    <td data-label="Importe">{{ number_format($gasto->importe, 2, '.', '') }}</td>
                    <td data-label="Acciones">
                        <a href="{{ route('gastoscajachica.show', $gasto->id) }}" class="btn btn-info btn-sm">Ver</a>
                        <a href="{{ route('gastoscajachica.edit', $gasto->id) }}" class="btn btn-warning btn-sm">Editar</a>
                        <form action="{{ route('gastoscajachica.destroy', $gasto->id) }}" method="POST"
                              class="d-inline" onsubmit="return confirm('¿Seguro que deseas eliminar este registro?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-gray-500">
                        No se registraron pagos para este período.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
@stop

@push('css')
<style>
/* === MODO OSCURO GENERAL === */
body, .content-wrapper {
    background-color: #121212 !important;
    color: #f0f0f0 !important;
}

.content-header h1 {
    color: #ffffff !important;
}

/* === TABLA === */
.table-gastos {
    width: 100%;
    border-collapse: collapse;
    background-color: #1e1e1e;
    color: #f0f0f0;
    border-radius: 8px;
    overflow: hidden;
}

.table-gastos th {
    background-color: #2a2a2a;
    color: #e0e0e0;
    padding: 10px;
    text-align: left;
}

.table-gastos td {
    padding: 10px;
    border-top: 1px solid #333;
}

.table-gastos tr:nth-child(even) {
    background-color: #242424;
}

.table-gastos tr:hover {
    background-color: #333;
}

/* === BOTONES PERSONALIZADOS === */
.table-gastos .btn.btn-info.btn-sm {
    background-color: #0dcaf0 !important;
    border-color: #0aa2c0 !important;
    color: #fff !important;
    filter: none !important;
}

.table-gastos .btn.btn-warning.btn-sm {
    background-color: #ffc107 !important;
    border-color: #e0a800 !important;
    color: #212529 !important;
    filter: none !important;
}

.table-gastos .btn.btn-danger.btn-sm {
    background-color: #dc3545 !important;
    border-color: #bd2130 !important;
    color: #fff !important;
    filter: none !important;
}

/* === Hover === */
.table-gastos .btn.btn-info.btn-sm:hover {
    background-color: #31d2f2 !important;
}

.table-gastos .btn.btn-warning.btn-sm:hover {
    background-color: #e0a800 !important;
}

.table-gastos .btn.btn-danger.btn-sm:hover {
    background-color: #bb2d3b !important;
}

/* === MEDIA QUERIES === */
@media (max-width: 768px) {
    .table-gastos,
    .table-gastos thead,
    .table-gastos tbody,
    .table-gastos tr,
    .table-gastos td {
        display: block;
        width: 100%;
    }

    .table-gastos thead {
        display: none;
    }

    .table-gastos tr {
        margin-bottom: 1rem;
        border: 1px solid #444;
        padding: 0.5rem;
        border-radius: 5px;
        background-color: #1e1e1e;
    }

    .table-gastos td {
        text-align: right;
        padding-left: 50%;
        position: relative;
        border: none;
        border-bottom: 1px solid #333;
    }

    .table-gastos td::before {
        content: attr(data-label);
        position: absolute;
        left: 0;
        width: 45%;
        padding-left: 0.5rem;
        font-weight: bold;
        text-align: left;
        color: #bbb;
    }

    .table-gastos td:last-child {
        border-bottom: 0;
    }

    .btn {
        display: block;
        width: 100%;
        margin-bottom: 0.25rem;
    }

    form.d-inline {
        display: block;
    }
}
</style>
@endpush

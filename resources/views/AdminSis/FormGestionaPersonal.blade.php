@extends('layouts.app')

@section('content')
<div id='principal'>

    <div class="card">
        <div class="card-header bg-sky-900 text-white text-center font-bold">
            FORMULARIO PARA LA GESTION DE PERSONAL
        </div>

        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>CI</th>
                        <th>Fecha Ingreso</th>
                        <th>Categoria</th>
                        <th>Cargo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($usuarios as $index => $user)
                        <tr>

                            <form action="{{ route('personal.store', $user->id) }}" method="POST">
                                @csrf

                                <td>{{ $index + 1 }}</td>
                                <td>{{ $user->nombre }}</td>
                                <td>{{ $user->apellido }}</td>
                                <td>{{ $user->ci }}</td>
                                <td>{{ $user->fec_ingreso }}</td>

                                <td>
                                    <select name="tipopersonal_id" class="form-control">
                                        <option value="">Seleccione</option>
                                        @foreach ($tipopersonal as $tipo)
                                            <option value="{{ $tipo->id }}">
                                                {{ $tipo->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>

                                <td>{{ $user->cargo }}</td>

                                <td class='grid grid-cols-3'>

                                    <button type="submit" class="btn btn-primary btn-sm w-[69px]">
                                        Registrar
                                    </button>

                            </form>

                            <a href="{{ route('personal.edit', $user->id) }}"
                               class="btn btn-warning btn-sm">
                                Editar
                            </a>

                            <form action="{{ route('personal.destroy', $user->id) }}" method="POST"
                                  style="display:inline-block;">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('¿Está seguro de eliminar este usuario?')">
                                    Eliminar
                                </button>
                            </form>

                            </td>

                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

    </div>

</div>

<style>
#principal {
    max-width: 1000px;
    margin: 30px auto;
    padding: 10px;
}

.table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 10px;
}

.table th,
.table td {
    border: 1px solid #cbd5e1;
    padding: 8px;
    text-align: center;
    font-size: 14px;
}

.table th {
    background-color: #e2e8f0;
}

.btn {
    display: inline-block;
    padding: 6px 10px;
    border-radius: 5px;
    font-size: 13px;
    text-decoration: none;
    font-weight: bold;
    cursor: pointer;
}

.btn-warning {
    background-color: #facc15;
    color: #000;
}

.btn-warning:hover {
    background-color: #eab308;
}

.btn-danger {
    background-color: #dc2626;
    color: #fff;
}

.btn-danger:hover {
    background-color: #b91c1c;
}

.btn-sm {
    padding: 4px 8px;
    font-size: 12px;
}

@media (max-width: 1024px) {

    .table th,
    .table td {
        font-size: 13px;
        padding: 6px;
    }
}

@media (max-width: 768px) {
    #principal {
        max-width: 95%;
        margin: 20px auto;
        padding: 5px;
    }

    .table th,
    .table td {
        font-size: 12px;
        padding: 5px;
    }

    .card-header {
        font-size: 16px;
        padding: 10px;
    }

    .btn {
        font-size: 11px;
        padding: 4px 6px;
    }
}

@media (max-width: 480px) {
    .table thead {
        display: none;
    }

    .table,
    .table tbody,
    .table tr,
    .table td {
        display: block;
        width: 100%;
    }

    .table tr {
        margin-bottom: 15px;
        border-bottom: 2px solid #e2e8f0;
    }

    .table td {
        text-align: right;
        padding-left: 50%;
        position: relative;
    }

    .table td::before {
        content: attr(data-label);
        position: absolute;
        left: 10px;
        width: 45%;
        text-align: left;
        font-weight: bold;
    }

    .btn {
        width: 48%;
        margin-top: 5px;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if (session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Correcto',
    text: "{{ session('success') }}"
});
</script>
@endif

@if (session('error'))
<script>
Swal.fire({
    icon: 'error',
    title: 'Error',
    text: "{{ session('error') }}"
});
</script>
@endif

@if ($errors->any())
<script>
Swal.fire({
    icon: 'error',
    title: 'Error',
    html: `{!! implode('<br>', $errors->all()) !!}`
});
</script>
@endif

@endsection
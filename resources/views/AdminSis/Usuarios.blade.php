@extends('layouts.app')
@section('content')
@csrf
<div><!---div principal--->
    <div class='card'> 
        @if (session('UsuarioCreado'))
            <div class="alert alert-success text-center">
                {{ session('UsuarioCreado') }}
            </div>
        @endif

        @if (session('UsuarioEliminado'))
            <div class="alert alert-danger text-center">
                {{ session('UsuarioEliminado') }}
            </div>
        @endif

        @if (session('UsuarioEditado'))
            <div class="alert alert-secondary text-center">
                {{ session('UsuarioEditado') }}
            </div>
        @endif     

        <div class='card-header bg-sky-900 text-white bold text-center'>
            FORMULARIO PARA GESTIÓN DE USUARIOS
        </div>

        <div class='w-full mb-2'>
            <form action="{{route('Usuarios.create')}}" method='get'>
                <button type='submit' class='btn-registrar bg-sky-900'>Registrar Usuario</button>
            </form>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped mb-2">
                    <thead>
                        <tr>
                            <th scope="col" class='text-center'>#</th>
                            <th scope="col" class='text-center'>Nombre</th>
                            <th scope="col" class='text-center'>Apellido</th>
                            <th scope="col" class='text-center'>C.I</th>
                            <th scope="col" class='text-center'>Fecha de Nacimiento</th>
                            <th scope="col" class='text-center'>Cargo</th>
                            <th scope="col" class='text-center'>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $cont = 0; @endphp
                        @foreach ($usuarios as $usr)
                            @php $cont++; @endphp
                            <tr>
                                <th scope="row" class='text-center'>{{ $cont }}</th>
                                <td class='text-left'>{{ $usr->nombre }}</td>
                                <td class='text-left'>{{ $usr->apellido }}</td>
                                <td class='text-left'>{{ $usr->ci }}</td>
                                <td class='text-center'>{{ \Carbon\Carbon::parse($usr->fecnac)->format('d-m-Y') }}</td>
                                <td class='text-left'>{{ $cargos->getCargo($usr->cargo_id) }}</td>
                                <td>
                                    <div class='acciones grid grid-cols-3 grid-rows-1'>
                                        <form action="{{route('Usuarios.show',$usr->id)}}" method='get'>
                                            <button type='submit' class='btn-ver' style='width:38px' ><i class="fas fa-eye"></i></button>
                                        </form>
                                        <form action="{{route('Usuarios.edit',$usr->id)}}" method='get'>
                                            @csrf
                                            <button type='submit' class='btn-editar'  style='width:38px'><i class="fas fa-pencil-alt"></i></button>
                                        </form>
                                        <form action="{{route('Usuarios.destroy',$usr->id)}}" method='post'>
                                            @csrf
                                            @method('DELETE')
                                            <button type='submit' class='btn-eliminar'  style='width:38px'><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div><!--- fin div card-body---->
    </div><!---fin class card---->
</div><!---fin div principal---->
@endsection

@section('css')
<style>
/* Botón registrar */
.btn-registrar {
    background-color: #182255;
    color: white;
    font-weight: bold;
    border-radius: 0.25rem;
    padding: 0.5rem;
    width: 100%;
    height: 2.5rem;
}

/* Botones de acción */
.acciones {
    display: flex;
    flex-wrap: wrap;
    gap: 0.25rem;
    justify-content: center;
}

.acciones form {
    margin: 0;
}

.btn-ver, .btn-editar, .btn-eliminar {
    width: 62px;
    padding: 0.25rem;
    border-radius: 0.25rem;
    color: white;
    text-align: center;
    font-size: 0.875rem;
}

.btn-ver { background-color: #374151; }
.btn-editar { background-color: #15803d; }
.btn-eliminar { background-color: #b91c1c; }

/* Tabla responsive */
.table-responsive {
    overflow-x: auto;
}

.table {
    width: 100%;
    border-collapse: collapse;
}

/* Media Queries */
@media (max-width: 768px) {
    .table thead {
        display: none;
    }

    .table, .table tbody, .table tr, .table td {
        display: block;
        width: 100%;
    }

    .table tr {
        margin-bottom: 1rem;
        border-bottom: 1px solid #ccc;
        padding-bottom: 0.5rem;
    }

    .table td {
        text-align: right;
        padding-left: 50%;
        position: relative;
    }

    .table td::before {
        content: attr(data-label);
        position: absolute;
        left: 0;
        width: 50%;
        padding-left: 0.5rem;
        font-weight: bold;
        text-align: left;
    }

    .acciones {
        justify-content: flex-start;
        flex-wrap: wrap;
        gap: 0.25rem;
    }

    .btn-ver, .btn-editar, .btn-eliminar {
        width: 100%;
        margin-bottom: 0.25rem;
    }
}
</style>
@endsection

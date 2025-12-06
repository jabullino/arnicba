@extends('layouts.app')
@section('content')
@csrf

<div class="personal-container"><!----div principal ------->

    <div class='card'>
        @if (session('aviso'))
            <div class="alert alert-warning bg-red-700 text-white bold text-center">
                {{ session('aviso') }}
            </div>
        @endif

        @isset($usuarios)
        <div class='card-header bg-sky-900 text-white bold text-center'>
            @if (session('PersonalCreado'))
                <div class="alert alert-success text-center">
                    {{ session('PersonalCreado') }}
                </div>
            @endif
            FORMULARIO DE REGISTRO DE PERSONAL
        </div>

        <div class='card-body'>
            <div class="table-container">
                <table class='table table-striped'>
                    <thead>
                        <tr>
                            <th scope="col" class='text-center'>#</th>
                            <th scope="col" class='text-center'>Nombre</th>
                            <th scope="col" class='text-center'>Apellido</th>
                            <th scope="col" class='text-center'>C.I</th>
                            <th scope="col" class='text-center w-36'>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($usuarios as $usr)
                            <tr>
                                <td class='text-center'>{{ $cont }}</td>
                                <td class='text-center'>{{ $usr->nombre }}</td>
                                <td class='text-center'>{{ $usr->apellido }}</td>
                                <td class='text-center'>{{ $usr->ci }}</td>
                                <td>
                                    <div class='inline-block w-32'>
                                        <form action="{{ route('PreparaPersonal', $usr->id) }}" method='get'>
                                            @csrf
                                            @method('GET')
                                            <button type='submit' class='bg-red-700 w-full rounded text-white text-md'>
                                                Verificar Datos
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @php $cont++; @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endisset
    </div><!---fin div card ---->

</div><!-------fin div principal------>

<style>
.personal-container {
    max-width: 900px;
    margin: 25px auto;
    padding: 0 15px;
}

.card {
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    overflow-x: auto;
}

.card-header {
    font-weight: bold;
    font-size: 18px;
    padding: 10px;
    text-align: center;
}

.table-container {
    width: 100%;
    overflow-x: auto;
}

.table {
    width: 100%;
    border-collapse: collapse;
    text-align: center;
}

.table th, .table td {
    padding: 10px;
    border: 1px solid #ccc;
}

button {
    padding: 6px 10px;
    border-radius: 6px;
    font-weight: bold;
    cursor: pointer;
}

.bg-sky-900 {
    background-color: #0c4a6e;
}

.bg-red-700 {
    background-color: #b91c1c;
}

/* --- Media Queries para hacerlo responsive --- */
@media (max-width: 768px) {
    .card-header {
        font-size: 16px;
    }

    .table thead {
        display: none;
    }

    .table tr {
        display: block;
        margin-bottom: 12px;
        border-bottom: 2px solid #e5e7eb;
    }

    .table td {
        display: flex;
        justify-content: space-between;
        text-align: right;
        padding: 8px;
        border: none;
        border-bottom: 1px solid #e5e7eb;
        font-size: 14px;
    }

    .table td::before {
        content: attr(data-label);
        font-weight: bold;
        text-transform: uppercase;
        text-align: left;
    }

    .table td:last-child {
        border-bottom: none;
    }

    button {
        font-size: 13px;
        padding: 8px;
    }
}

@media (max-width: 480px) {
    .personal-container {
        padding: 0 10px;
    }

    .card-header {
        font-size: 15px;
    }

    .table td {
        font-size: 13px;
    }
}
</style>
@endsection

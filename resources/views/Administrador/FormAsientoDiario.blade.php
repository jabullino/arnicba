@extends('layouts.app')
@section('content')
@csrf
<div><!---div principal --->

    <div class='card'><!---div card --->
        <div class='card-header text-center bold w-[450px] mx-auto'>
            FORMULARIO PARA GESTIÓN DE ASIENTOS DE DIARIO
        </div>

        <div class='w-full'>
            <form action="{{route('AsientoDiario.create')}}" method='get'>
                <button type='submit' class='bg-green-700 w-gull bold text-white rounded-md mt-1 w-full'>Registrar Usuario</button>
            </form>
        </div>

        <div>
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
                <tbody class='tarjeta'>
                    @foreach ($asientos as $asi)
                        <tr>
                            @php $cont++; @endphp
                            <th scope="row" class='text-center' data-label="#"> {{ $cont }}</th>
                            <td class='text-left' data-label="Nombre">{{ $asi->nombre }}</td>
                            <td class='text-left' data-label="Apellido">{{ $asi->apellido }}</td>
                            <td class='text-left' data-label="C.I">{{ $asi->ci }}</td>
                            <td class='text-center' data-label="Fecha de Nacimiento">{{ $asi->fecnac }}</td>
                            <td data-label="Cargo">{{ $asi->cargo ?? '—' }}</td>
                            <td data-label="Acciones">
                                <div class='inline-block'>
                                    <form action="{{route('Usuarios.show',$usr->id)}}" method='get'>
                                        <button type='submit' class='bg-gray-700 w-24 rounded text-white'>Ver</button>
                                    </form>
                                </div>
                                <div class='inline-block'>
                                    <form action="{{route('Usuarios.edit',$usr->id)}}" method='get'>
                                        @csrf
                                        <button type='submit' class='bg-green-700 w-24 rounded text-white text-center'>Editar</button>
                                    </form>
                                </div>
                                <div class='inline-block'>
                                    <form action="{{route('Usuarios.destroy',$usr->id)}}" method='post'>
                                        @csrf
                                        @method('DELETE')
                                        <button type='submit' class='bg-red-700 w-24 rounded text-white'>Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div><!--- fin div card-body---->
    </div><!--- fin div card ---->
</div><!---fin div principal --->

<style>
    /* ---------------- Responsive ---------------- */
    @media (max-width: 1024px) {
        .card {
            width: 90%;
            margin: 0 auto;
        }
        .card-header {
            font-size: 1.1rem;
        }
    }

    @media (max-width: 768px) {
        table {
            display: block;
            width: 100%;
            overflow-x: auto;
            border-collapse: collapse;
        }
        thead {
            display: none;
        }
        tbody tr {
            display: block;
            margin-bottom: 1rem;
            border: 1px solid #ccc;
            border-radius: 0.25rem;
            padding: 0.5rem;
        }
        tbody td, tbody th {
            display: flex;
            justify-content: space-between;
            padding: 0.25rem 0.5rem;
            border: none;
            border-bottom: 1px solid #eee;
        }
        tbody td:last-child {
            flex-direction: column;
            gap: 0.25rem;
        }
        tbody td::before, tbody th::before {
            content: attr(data-label);
            font-weight: bold;
            flex: 1;
        }
        .inline-block {
            display: block;
            margin-bottom: 0.25rem;
        }
        button {
            width: 100%;
        }
    }

    @media (max-width: 480px) {
        .card-header {
            font-size: 1rem;
            padding: 0.5rem;
        }
        td, th {
            font-size: 12px;
        }
        button {
            font-size: 12px;
            padding: 0.25rem 0.5rem;
        }
    }
</style>
@endsection

@extends('layouts.app')
@section('content')
@php
    use Carbon\Carbon;
@endphp
@csrf
<div><!---div principal--->

    <div class='card'>
        
        @if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: "{{ session('success') }}",
            confirmButtonColor: '#3085d6',
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
            confirmButtonColor: '#d33',
            confirmButtonText: 'Aceptar'
        });
    </script>
@endif

        <div class='card-header bg-sky-900 text-white bold text-center'>
            FORMULARIO PARA GESTIÓN DE ASIENTOS DE DIARIO
        </div>

        <div class='w-full'>
            <form action="{{route('Asientos.create')}}" method='get'>
                <button type='submit' class='bg-green-700 w-gull bold text-white rounded-md mt-1 w-full'>Registrar Asiento Diario</button>
            </form>
        </div>

        <div class="card-body">

            <table class="table table-striped mb-2 pagination">
                <thead>
                    <tr>
                        <th scope="col" class='text-center w-8'>Num. Asiento</th>
                        <th scope="col" class='text-center'>Fecha</th>
                        <th scope="col" class='text-center'>Factura</th>
                        <th scope="col" class='text-center'>Recibo</th>
                        <th scope="col" class='text-center'>Cuenta</th>
                        <th scope="col" class='text-center w-14'>Subcuenta</th>
                        <th scope="col" class='text-center'>Importe Bs.</th>
                        <th scope="col" class='text-center'>Importe $us</th>
                        <th scope="col" class='text-center'>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($asientos as $asi)
                        <tr>
                            <th scope="row" class='text-center' data-label="Num. Asiento">{{ $asi->id}}</th>
                            <td class='text-left' data-label="Fecha">{{ \Carbon\Carbon::parse($asi->fec_asiento)->format('d-m-Y') }}</td>
                            <td class='text-left' data-label="Factura">{{ $asi->factura }}</td>
                            <td class='text-left' data-label="Recibo">{{ $asi->recibo }}</td>
                            <td class='text-left' data-label="Cuenta">{{ $cuentasaux->getCuenta($asi->cuenta) }}</td>
                            <td class='text-left' data-label="Subcuenta">{{ $subcuentasaux->getSubcuenta($asi->sub_cuenta) }}</td>
                            <td class='text-right' data-label="Importe Bs.">{{ $asi->monto_bs }}</td>
                            <td class='text-right' data-label="Importe $us">{{ $asi->monto_sus }}</td>
                            <td data-label="Acciones">
                                <div class='inline-block w-6 ml-8'>
                                    <form action="{{route('Asientos.show',$asi->id)}}" method='get'>
                                        <button type="submit" class="btn btn-link bg-gray rounded">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </form>
                                </div>
                                <div class='inline-block w-6 ml-10'>
                                    <form action="{{route('Asientos.edit',$asi->id)}}" method='get'>
                                        @csrf
                                        <button type="submit" class="btn btn-link bg-green-200 rounded">
                                            <i class="far fa-edit"></i>
                                        </button>
                                    </form>
                                </div>
                                <div class='inline-block w-8 ml-11'>
                                    <form action="{{route('Asientos.destroy',$asi->id)}}" method='post'>
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link bg-red-500 rounded">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $asientos->links('pagination::bootstrap-5') }}
        </div><!--- fin div card-body---->

    </div><!---fin class card---->

</div><!---fin div principal---->

<style>
    /* ------------- Responsive ----------------- */
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

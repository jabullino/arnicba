@extends('layouts.app')
@section('content')
    <style>
        /* Media queries para responsive */
        @media (max-width: 1024px) {
            table.table {
                display: block;
                width: 100% !important;
                overflow-x: auto;
            }
            table thead, table tbody, table th, table td, table tr {
                display: block;
            }
            table thead tr {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }
            table tr {
                margin-bottom: 1rem;
                border: 1px solid #ddd;
                padding: 0.5rem;
            }
            table td {
                border: none;
                position: relative;
                padding-left: 50%;
                text-align: left;
            }
            table td::before {
                position: absolute;
                top: 0;
                left: 0;
                width: 45%;
                padding-left: 0.5rem;
                font-weight: bold;
                white-space: nowrap;
            }
            table td:nth-of-type(1)::before { content: "Num."; }
            table td:nth-of-type(2)::before { content: "Seleccionar"; }
            table td:nth-of-type(3)::before { content: "Nombre"; }
            table td:nth-of-type(4)::before { content: "Apellido"; }
            table td:nth-of-type(5)::before { content: "Cargo"; }
            table td:nth-of-type(6)::before { content: "Líquido Pagable"; }
            button {
                font-size: 0.875rem !important;
                padding: 0.5rem !important;
            }
        }

        @media (max-width: 640px) {
            button {
                width: 100% !important;
                font-size: 0.875rem !important;
                padding: 0.5rem !important;
            }
        }
    </style>

    <div name='principal'>
        <form action="{{ route('formpagasueldos') }}" method="POST">
            @csrf
            <div class='card'>
                <div id='errores' class='bg-red-700 text-white text-center bold'>
                    @if ($errors->has('escogidos'))
                        <div class='bg-red-700 text-white text-center bold'>
                            {{ $errors->first('escogidos') }}
                        </div>
                    @endif
                    @if ($errors->has('mespagado'))
                        <div class='bg-red-700 text-white text-center bold'>
                            {{ $errors->first('mespagado') }}
                        </div>
                    @endif
                </div>

                <div class='card-header bg-sky-900 text-white bold text-center'>
                    FORMULARIO DE PAGO RÁPIDO DE SUELDOS
                </div>

                <div class='card-body'>
                    <table class='table table-striped'>
                        <thead>
                            <tr>
                                <th class='text-center'>Num.</th>
                                <th class='text-center'>SELECCIONAR<br>Todos <input type="checkbox" name='todos' id='todos'></th>
                                <th class='text-center'>NOMBRE</th>
                                <th class='text-center'>APELLIDO</th>
                                <th class='text-center'>CARGO</th>
                                <th class='text-center'>LÍQUIDO PAGABLE</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sueldos as $dat)
                                <tr>
                                    <td class='text-center'>{{ $cont }}</td>
                                    <td class='text-center'>
                                        <input type="checkbox" name="escogidos[]" value="{{ $dat->id }}">
                                        <input type="hidden" name="cargos[]" value="{{ $dat->cargo_id }}">
                                    </td>
                                    <td class='text-center'>{{ $dat->nombre }}</td>
                                    <td class='text-center'>{{ $dat->apellido }}</td>
                                    <td class='text-center'>{{ $dat->nombre_cargo }}</td>
                                    <td class='text-center'>{{ $dat->monto }}</td>
                                </tr>
                                @php $cont++; @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div><!--fin div card-body --->

                <input type="hidden" name="fechapago" value='{{$fechapago}}'>
                <button type='submit' class='w-full bg-sky-900 text-white bold'>Pagar Sueldos</button>
            </div><!--fin div class card --->
        </form>
    </div><!--fin div principal --->
@endsection

@section('js')
    <script>
        document.getElementById('todos').addEventListener('change', function() {
            let checkboxes = document.querySelectorAll('input[name^="escogidos["]');
            checkboxes.forEach(chk => chk.checked = this.checked);
        });

        document.addEventListener("DOMContentLoaded", function() {
            let alertBox = document.getElementById('errores');
            if (alertBox) {
                setTimeout(() => {
                    alertBox.style.transition = "opacity 1s ease";
                    alertBox.style.opacity = "0";
                    setTimeout(() => alertBox.remove(), 1000);
                }, 5000);
            }
        });
    </script>
@endsection

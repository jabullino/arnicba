@extends('layouts.app')
@section('content')
    <div name='principal' class='mx-auto' style='width:400px'>

        <form action="{{ route('seleccionfechaasueldos') }}" method="POST">
            @method('POST')
            @csrf
            <div class='card' style='width:450px'>

                <div class='card-header bg-red-700 text-white text-center bold text-lg' style='width:450px'>
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
                    <div class='bg-red-700 text-white text-center bold'>
                        ESCOJA UNA FECHA PARA PAGO DE SUELDOS
                    </div>
                    @if ($errors->has('fechapago'))
                        <div class='bg-blue-500 text-white text-center bold'>
                            {{ $errors->first('fechapago') }}
                        </div>
                    @endif
                </div>

                <div class='card-body w-center' style='width:450px'>
                    <div class='card-header'>
                        <label for="fechapago" class='bg-sky-900 text-white bold w-48 h-8 text-center' style='width:400px'>
                            FECHA DE PAGO
                        </label>
                        <br>
                        <input type="date" class='form-control' name="fechapago" style='width:400px'>
                        <button type="submit" class='bg-sky-900 h8 text-white bold text-center mt-2' style='width:400px'>
                            Consultar
                        </button>
                    </div>
                </div>
            </div>
        </form>

    </div>

    <style>
        /* Media queries para responsive */
        @media (max-width: 768px) {
            [name='principal'], .card, .card-header, .card-body, input, button, label {
                width: 90% !important;
                margin-left: auto !important;
                margin-right: auto !important;
            }
        }

        @media (max-width: 480px) {
            .text-lg {
                font-size: 0.875rem !important;
            }
            .h-8, .h8 {
                height: 2rem !important;
            }
        }
    </style>
@endsection

@section('js')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let alertBox = document.getElementById('sueldospagados');
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

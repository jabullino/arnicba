@extends('layouts.app')

@section('content')
    <div name='principal'>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card max-w-sm w-full mx-auto">
    <div class="card-header text-center text-white font-bold mt-4 bg-slate-700">
        FORMULARIO RESUMEN DE GASTO MENSUAL
    </div>

    <div class="card-body bg-slate-700 p-4">
        <form action="{{ route('muestrareportemensual') }}" method="POST">
            @csrf

            <div class="bg-gray-500 p-4 rounded">

                <!-- FECHA INICIO -->
                <label class="block text-white text-center mb-1">
                    FECHA DE INICIO
                </label>
                <input type="date" name="fecinicio"
                    class="w-full rounded text-sky-900 mb-3">

                <!-- FECHA FIN -->
                <label class="block text-white text-center mb-1">
                    FECHA FIN
                </label>
                <input type="date" name="fecfin"
                    class="w-full rounded text-sky-900 mb-3">

                <!-- MONEDA -->
                <label class="block text-white text-center mb-1">
                    MONEDA
                </label>
                <select name="moneda"
                    class="w-full rounded text-lg mb-4">
                    <option value="default">Escoge Moneda</option>
                    <option value="bolivianos">Bolivianos</option>
                    <option value="dolares">Dólares</option>
                </select>

                <!-- BOTÓN -->
                <button type="submit"
                    class="w-full bg-sky-900 text-white py-2 rounded">
                    Consultar
                </button>

            </div>
        </form>
    </div>
</div>
    </div>

    <style>
        /* Media queries para responsive */
        @media (max-width: 768px) {
            .card {
                width: 90% !important;
            }
            .card-header, .card-body {
                width: 100% !important;
            }
            input, select, button {
                width: 90% !important;
                margin: 0 auto !important;
            }
            .w-[250px], .w-[325px], .w-[350px] {
                width: 90% !important;
            }
            .mx-[38px], .mx-[40px] {
                margin-left: auto !important;
                margin-right: auto !important;
            }
        }

        @media (max-width: 480px) {
            .text-lg {
                font-size: 0.875rem !important;
            }
            .h-[270px] {
                height: auto !important;
            }
            .mt-64 {
                margin-top: 2rem !important;
            }
        }
    </style>
@endsection

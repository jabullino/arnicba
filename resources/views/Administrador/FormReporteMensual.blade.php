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

        <div class='card w-[350px] mx-auto'>
            <div class='card-header text-center text-white bold w-[350px] mt-4 bold text-center bg-slate-700'>
                FORMULARIO RESUMEN DE GASTO MENSUAL
            </div>

            <div class='card-body bg-slate-700'>
                <form action="{{ route('muestrareportemensual') }}" method='POST'>
                    @csrf
                    <div class='bg-gray-500 h-[270px] w-[325px] mx-auto'>
                        <div class='mb-2 h-3'></div>
                        <div class='block w-64 h-14 mx-16 mb-8'>
                            <div class='text-white bold-md text-lg w-[250px] text-center bg-slate-700'>
                                FECHA DE INICIO
                            </div>
                            <div class='text-white bold-md text-lg w-[250px] text-center bg-slate-700'>
                                <input type="date" id="fecinicio" name="fecinicio"
                                    class='rounded w-[250px] text-sky-900 text-lg rounded form-control'>
                            </div>
                            @if ($errors->has('fecinicio'))
                                <div style="color: red;" class='bg-red-500 text-white text-lg w-[250px]'>
                                    {{ $errors->first('fecinicio') }}
                                </div>
                            @endif

                            <div class='text-white bold-md text-lg w-[250px] mt-4 text-center bg-slate-700'>
                                FECHA FIN
                            </div>
                            <div>
                                <input type="date" id="fecfin" name="fecfin"
                                    class='rounded text-sky-900 text-lg rounded w-[250px] form-control'>
                            </div>
                            @if ($errors->has('fecfin'))
                                <div style="color: red;" class='bg-red-500 text-white text-lg w-[250px]'>
                                    {{ $errors->first('fecfin') }}
                                </div>
                            @endif

                            <div class='text-white bold-md text-lg w-[250px] mt-4 text-center bg-slate-700'>
                                MONEDA
                            </div>
                            <div class='w-[250px]'>
                                <select name="moneda" id="moneda" class='form-control w-[250px] text-lg'>
                                    <option value="default">Escoge Moneda</option>
                                    <option value="bolivianos">Bolivianos</option>
                                    <option value="dolares">Dólares</option>
                                </select>
                            </div>
                            @if ($errors->has('moneda'))
                                <div style="color: red;" class='bg-red-500 text-white text-lg w-[250px]'>
                                    {{ $errors->first('moneda') }}
                                </div>
                            @endif
                        </div>

                        <div class='w-full mt-64'>
                            <button type='submit'
                                class='bg-sky-900 text-white bold w-[250px] form-control mx-[38px]'>Consultar</button>
                        </div>
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

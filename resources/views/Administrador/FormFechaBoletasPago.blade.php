@extends('layouts.app')
@section('content')

<style>
    /* General responsive */
    @media (max-width: 1024px) {
        #principal {
            width: 90% !important;
            margin: auto !important;
        }
        .form-control {
            width: 100% !important;
        }
        button {
            width: 100% !important;
        }
    }

    @media (max-width: 640px) {
        #principal {
            width: 95% !important;
        }
        label {
            font-size: 0.875rem !important;
        }
        select.form-control {
            width: 100% !important;
            font-size: 0.875rem !important;
        }
        button {
            width: 100% !important;
            font-size: 0.875rem !important;
        }
    }
</style>

<div name='principal' id='principal' class='mx-auto w-[450px]'>
    <form action="creaboletas" method='post'>
        @csrf
        <div class='card mx-auto'>
            <div class="card-header text-center text-white font-bold bg-sky-900">
                ESCOJA UNA FECHA PARA IMPRIMIR BOLETAS DE PAGO
            </div><!--fin div card-header--->

            <div class="card-body">
                <label for="fechapago" class='bg-sky-900 text-white bold w-[450px] h-8 text-center'>FECHA
                    DE PAGO</label>
                <br>
                <select name="gestion" id="gestion" class='form-control w-[425px]'>
                    <option value="default">Escoge una Gestion</option>
                    @foreach ($gestiones as $gestion)
                        <option value="{{$gestion->id}}">{{$gestion->nombre}}</option>
                    @endforeach
                </select>
                <br>
                <select name="mes" id="mes" class='form-control w-[425px]'>
                    <option value="default">Escoge un mes</option>
                    <option value="ENERO">ENERO</option>
                    <option value="FEBRERO">FEBRERO</option>
                    <option value="MARZO">MARZO</option>
                    <option value="ABRIL">ABRIL</option>
                    <option value="MAYO">MAYO</option>
                    <option value="JUNIO">JUNIO</option>
                    <option value="JULIO">JULIO</option>
                    <option value="AGOSTO">AGOSTO</option>
                    <option value="SEPTIEMBRE">SEPTIEMBRE</option>
                    <option value="OCTUBRE">OCTUBRE</option>
                    <option value="NOVIEMBRE">NOVIEMBRE</option>
                    <option value="DICIEMBRE">DICIEMBRE</option>
                </select>

                <button type="submit"
                    class='bg-sky-900 h8 text-white bold text-center mt-2 rounded-md'
                    style='width:425px'>Consultar</button>
            </div><!---fin div card-body--->
        </div><!--fin div card ---->
    </form>
</div><!--fin div principal --->

@endsection

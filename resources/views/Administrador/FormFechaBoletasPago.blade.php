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
                <select name="mes" required class='form-control'>
                    <option value="1">ENERO</option>
                    <option value="2">FEBRERO</option>
                    <option value="3">MARZO</option>
                    <option value="4">ABRIL</option>
                    <option value="5">MAYO</option>
                    <option value="6">JUNIO</option>
                    <option value="7">JULIO</option>
                    <option value="8">AGOSTO</option>
                    <option value="9">SEPTIEMBRE</option>
                    <option value="10">OCTUBRE</option>
                    <option value="11">NOVIEMBRE</option>
                    <option value="12">DICIEMBRE</option>
                 </select>
                <button type="submit"
                    style='width:425px'>Consultar</button>
            </div><!---fin div card-body--->
        </div><!--fin div card ---->
    </form>
</div><!--fin div principal --->

@endsection

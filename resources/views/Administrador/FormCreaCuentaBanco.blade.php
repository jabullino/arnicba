@extends('layouts.app')
@section('content')
<style>
    /* ------------------ General ------------------ */
    .card {
        max-width: 540px;
        margin: auto;
    }
    .form-control {
        width: 100%;
        padding: 6px 8px;
        box-sizing: border-box;
    }
    button {
        cursor: pointer;
    }

    /* ------------------ Responsive ------------------ */
    @media (max-width: 1024px) {
        .card {
            width: 90%;
        }
        .card-header {
            font-size: 1.2rem;
        }
        label {
            font-size: 0.95rem;
        }
        .form-control {
            padding: 5px;
        }
        button {
            width: 100%;
        }
    }

    @media (max-width: 768px) {
        .card {
            width: 95%;
        }
        .card-header {
            font-size: 1rem;
        }
        label {
            font-size: 0.9rem;
        }
        .form-control {
            padding: 4px;
        }
        button {
            font-size: 0.9rem;
        }
    }

    @media (max-width: 480px) {
        .card {
            width: 100%;
        }
        .card-header {
            font-size: 0.85rem;
        }
        label {
            font-size: 0.8rem;
        }
        .form-control {
            padding: 3px;
            font-size: 0.85rem;
        }
        button {
            font-size: 0.85rem;
        }
    }
</style>

<form action="{{ route('CuentaBanco.store') }}" method="POST">
    @csrf
    <div><!---div principal---->
        <div class='card'>
            @if (session('CuentaCreada'))
                <div class="alert alert-success text-center">
                    {{ session('CuentaCreada') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class='card-body'>
                <div class='card-header bg-sky-900 text-white bold text-center mb-4'>
                    FORMULARIO PARA CREACIÓN DE CUENTAS BANCARIAS
                </div> <!--- fin div card-header--->

                <div class='card-body bg-sky-900'>

                    <div class='from-group bg-white-900'>
                        <div class='mb-3'>
                            <label for='banco' class='text-white bold'>Banco</label>
                            <select id="banco" name="banco" class='form-control'>
                                <option value="default">Seleccionar Banco</option>
                                @foreach ($banco as $ban)
                                    <option value="{{ $ban->id }}">{{ $ban->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class='mb-3'>
                            <label for='tipocuenta' class='text-white bold'>Tipo de Cuenta</label>
                            <select id="tipocuenta" name="tipocuenta" class='form-control'>
                                <option value="default">Seleccionar Tipo de Cuenta</option>
                                @foreach ($tipocuenta as $tipocu)
                                    <option value="{{ $tipocu->id }}">{{ $tipocu->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class='mb-3'>
                            <label for='tipomoneda' class='text-white bold'>Tipo de Moneda</label>
                            <select id="tipomoneda" name="tipomoneda" class='form-control'>
                                <option value="default">Seleccionar Tipo de Moneda</option>
                                @foreach ($tipomoneda as $tipomo)
                                    <option value="{{ $tipomo->id }}">{{ $tipomo->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class='mb-3'>
                            <label for='numcuenta' class='text-white bold'>Número de Cuenta</label>
                            <input type="text" name="numcuenta" id="numcuenta" class='form-control'>
                        </div>

                        <div class='mt-3'>
                            <button type='submit' class='bg-gray-800 text-white bold text-center w-full form-control'>
                                Registrar Cuenta
                            </button>
                        </div>
                    </div>

                </div>
            </div><!-- fin div card-body --->
        </div><!--fin div class card--->
    </div><!--fin div principal--->
</form>
@endsection

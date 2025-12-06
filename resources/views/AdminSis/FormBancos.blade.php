@extends('layouts.app')
@section('content')
@csrf

<div class="cuentas-container"><!---div principal---->

    <div class='card'>
        <div class='card-header bg-sky-900 text-white bold text-center'>
            FORMULARIO PARA GESTIÓN CUENTAS BANCARIAS
        </div> <!--- fin div class card-header--->

        <div class='card-body'>
            <form action="{{ route('Bancos.create') }}" method='get'>
                <button type='submit' class='bg-sky-700 w-gull bold text-white rounded-md mt-1 w-full h-10'>
                    Registrar Nueva Cuenta Bancaria
                </button>
            </form>

            <div class='card-body'>
                <div class='form-group-grid'>

                    <div class='form-control mb-2'>
                        <label for='banco'>Banco</label>
                        <select id="banco" name="banco" class='form-control'>
                            <option value="">Seleccionar Banco</option>
                            @foreach ($bancos as $banco)
                                <option value="{{ $banco->id }}">{{ $banco->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class='form-control mb-2'>
                        <label for='tipocuenta'>Tipo de Cuenta</label>
                        <select id="tipocuenta" name="tipocuenta" class='form-control'>
                            <option value="default">Seleccionar Tipo de Cuenta</option>
                            @foreach ($tipocuenta as $tipocu)
                                <option value="{{ $tipocu->id }}">{{ $tipocu->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class='form-control mb-2'>
                        <label for='tipomoneda'>Tipo de Moneda</label>
                        <select id="tipomoneda" name="tipomoneda" class='form-control'>
                            <option value="default">Seleccionar Tipo de Moneda</option>
                            @foreach ($tipomoneda as $tipomo)
                                <option value="{{ $tipomo->id }}">{{ $tipomo->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>
            </div>
        </div><!-- fin div card-body --->
    </div><!--fin div class card--->

</div><!--fin div principal--->

<style>
.cuentas-container {
    max-width: 800px;
    margin: 25px auto;
    padding: 0 15px;
}

.card {
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    padding: 15px;
}

.card-header {
    font-weight: bold;
    font-size: 18px;
    text-align: center;
    margin-bottom: 15px;
}

.form-group-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
}

.form-control {
    width: 100%;
    padding: 8px 10px;
    border-radius: 6px;
    border: 1px solid #ccc;
    box-sizing: border-box;
}

button {
    padding: 10px;
    border-radius: 6px;
    font-weight: bold;
    cursor: pointer;
}

.bg-sky-700 {
    background-color: #0369a1;
}

.bg-sky-900 {
    background-color: #0c4a6e;
}

/* --- Media Queries para hacerlo responsive --- */
@media (max-width: 1024px) {
    .form-group-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    .card-header {
        font-size: 16px;
    }

    .form-control, select {
        font-size: 14px;
        padding: 6px;
    }

    button {
        font-size: 14px;
        padding: 8px;
    }
}
</style>
@endsection

@extends('layouts.app')

@section('content')
<div id='principal'>
    <form action="{{ route('personal.update', $usuario->id) }}" method="POST">
        @csrf
        @method('PATCH') <!-- Necesario para actualizar -->

        <div class="card">
            <div class="card-header bg-sky-900 text-white text-center font-bold">
                FORMULARIO PARA LA GESTION DE PERSONAL
            </div><!--fin div card-header --->

            <div class="card-body">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" value="{{ $usuario->nombre }}" required>
                </div>

                <div class="mb-3">
                    <label for="apellido" class="form-label">Apellido</label>
                    <input type="text" name="apellido" id="apellido" class="form-control" value="{{ $usuario->apellido }}" required>
                </div>

                <div class="mb-3">
                    <label for="ci" class="form-label">CI</label>
                    <input type="text" name="ci" id="ci" class="form-control" value="{{ $usuario->ci }}" required>
                </div>

                <div class="mb-3">
                    <label for="fec_ingreso" class="form-label">Fecha de Ingreso</label>
                    <input type="date" name="fec_ingreso" id="fec_ingreso" class="form-control" value="{{ $usuario->fec_ingreso }}" required>
                </div>

                <div class="mb-3">
                    <label for="cargo_id" class="form-label">Cargo</label>
                    <select name="cargo_id" id="cargo_id" class="form-select" required>
                        @foreach($cargos as $id => $nombre)
                            <option value="{{ $id }}" {{ $usuario->cargo_id == $id ? 'selected' : '' }}>{{ $nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="user_cod" class="form-label">User Cod</label>
                    <input type="text" name="user_cod" id="user_cod" class="form-control" value="{{ $usuario->user_cod }}" required>
                </div>

                <!-- NUEVO CAMPO: Último Monto -->
                <div class="mb-3">
                    <label for="ultimo_monto" class="form-label">Último Monto</label>
                    <input type="text" name="ultimo_monto" id="ultimo_monto" class="form-control" value="{{ $usuario->ultimo_monto ?? '0.00' }}">
                </div>

                <div class="button-group">
                    <button type="submit" class="btn-submit">Actualizar</button>
                    <a href="{{ route('Personal') }}" class="btn-cancel">Cancelar</a>
                </div>
            </div><!---fin div card-body---->
        </div><!--fin div card --->
    </form>
</div><!----- fin div principal ---->

<style>
/* --- Estilos base --- */
#principal {
    max-width: 600px;
    margin: 40px auto;
    padding: 15px;
}

.card {
    background-color: #ffffff;
    border-radius: 10px;
    box-shadow: 0px 3px 8px rgba(0,0,0,0.1);
    overflow: hidden;
}

.card-header {
    font-weight: bold;
    padding: 12px;
    font-size: 18px;
}

.card-body {
    padding: 20px;
}

.mb-3 {
    margin-bottom: 15px;
}

.form-label {
    display: block;
    font-weight: bold;
    margin-bottom: 6px;
}

.form-control,
.form-select {
    width: 100%;
    padding: 8px 10px;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    font-size: 14px;
    outline: none;
    transition: border 0.2s;
}

.form-control:focus,
.form-select:focus {
    border-color: #0c4a6e;
}

.button-group {
    display: flex;
    justify-content: space-between;
    gap: 10px;
}

.btn-submit {
    background-color: #0c4a6e;
    color: #fff;
    border: none;
    padding: 10px;
    font-weight: bold;
    border-radius: 6px;
    cursor: pointer;
    width: 49%;
    transition: background-color 0.2s;
}

.btn-submit:hover {
    background-color: #075985;
}

.btn-cancel {
    display: inline-block;
    background-color: #6b7280;
    color: #fff;
    text-align: center;
    padding: 10px;
    font-weight: bold;
    border-radius: 6px;
    text-decoration: none;
    width: 49%;
    transition: background-color 0.2s;
}

.btn-cancel:hover {
    background-color: #4b5563;
}

/* --- Media Queries para hacerlo responsive --- */
@media (max-width: 768px) {
    #principal {
        max-width: 90%;
        margin-top: 20px;
        padding: 10px;
    }

    .card-header {
        font-size: 16px;
        padding: 10px;
    }

    .form-control,
    .form-select {
        font-size: 13px;
        padding: 7px;
    }

    .button-group {
        flex-direction: column;
    }

    .btn-submit,
    .btn-cancel {
        width: 100%;
    }
}

@media (max-width: 480px) {
    .card-header {
        font-size: 15px;
    }

    .form-label {
        font-size: 13px;
    }

    .form-control,
    .form-select {
        font-size: 12px;
    }
}
</style>
@endsection

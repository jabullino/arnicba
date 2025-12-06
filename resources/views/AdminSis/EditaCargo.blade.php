@extends('layouts.app')
@section('content')
<div class="cargo-edit-container">
    <div class="card">
        <div class="card-header text-center bold">
            FORMULARIO DE EDICION DE CARGOS
        </div>

        <div class="card-body">
            <form action="{{ route('Cargos.update', $cargo->id) }}" method="post">
                @csrf
                @method('PATCH')

                <div class="form-group mb-2">
                    <label for="cargo">Cargo</label>
                    <input type="text" name="cargo" value="{{ $cargo->nombre }}" class="form-control">

                    <label for="haberbasico">Haber Básico</label>
                    <input type="text" name="haberbasico" value="{{ $cargo->haberbasico }}" class="form-control">

                    @if ($errors->has('cargo'))
                        <div class="error-text">{{ $errors->first('cargo') }}</div>
                    @endif
                    @if ($errors->has('haberbasico'))
                        <div class="error-text">{{ $errors->first('haberbasico') }}</div>
                    @endif
                </div>

                <div class="form-group mb-2">
                    <button class="btn-submit w-full">Actualizar</button>
                </div>

            </form>
        </div>
    </div>
</div>

<style>
.cargo-edit-container {
    max-width: 500px;
    margin: 50px auto;
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

.form-control {
    width: 100%;
    padding: 8px 10px;
    margin-top: 5px;
    margin-bottom: 10px;
    border-radius: 6px;
    border: 1px solid #ccc;
    box-sizing: border-box;
}

.btn-submit {
    background-color: #0ea5e9; /* sky-900 */
    color: white;
    font-weight: bold;
    padding: 10px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}

.error-text {
    color: red;
    font-size: 14px;
    margin-top: 2px;
}

/* Media Queries */
@media (max-width: 768px) {
    .cargo-edit-container {
        margin: 30px 10px;
    }
    .card-header {
        font-size: 16px;
    }
    .form-control {
        padding: 6px 8px;
    }
    .btn-submit {
        padding: 8px;
        font-size: 14px;
    }
}

@media (max-width: 480px) {
    .card-header {
        font-size: 14px;
    }
    .form-control {
        padding: 5px 6px;
        font-size: 14px;
    }
    .btn-submit {
        padding: 6px;
        font-size: 13px;
    }
}
</style>
@endsection

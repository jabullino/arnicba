@extends('layouts.app')
@section('content')
<div class="cargo-form-container">
    <div class="card">
        <div class="card-header text-center bold">
            FORMULARIO DE REGISTRO DE CARGOS
        </div>

        <div class="card-body">
            <form action="{{route('Cargos.store')}}" method="post">
                @csrf

                <div class="form-group mb-3">
                    <label for="cargo">Cargo</label>
                    <input type="text" name="cargo" class="form-control">

                    <label for="haberbasico">Haber Basico</label>
                    <input type="text" name="haberbasico" class="form-control">

                    @if ($errors->has('cargo'))
                        <div class="error-message">{{ $errors->first('cargo') }}</div>
                    @endif
                    @if ($errors->has('haberbasico'))
                        <div class="error-message">{{ $errors->first('haberbasico') }}</div>
                    @endif
                </div>

                <div class="form-group mb-2">
                    <button type="submit" class="btn btn-sky w-full">Registrar</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ====== ESTILOS RESPONSIVE ====== --}}
<style>
.cargo-form-container {
    max-width: 420px;
    margin: 80px auto 20px auto;
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
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    font-weight: 600;
    margin-bottom: 5px;
}

.form-group input {
    width: 100%;
    padding: 8px 10px;
    border-radius: 6px;
    border: 1px solid #ccc;
    margin-bottom: 10px;
    box-sizing: border-box;
}

.error-message {
    color: red;
    font-size: 13px;
    margin-bottom: 5px;
}

button.btn {
    padding: 10px 12px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    font-weight: bold;
    font-size: 15px;
    background-color: #0f172a;
    color: #fff;
}

/* ===== Media Queries ===== */
@media (max-width: 768px) {
    .card-header {
        font-size: 16px;
    }
    button.btn {
        font-size: 14px;
        padding: 8px 10px;
    }
    .form-group input {
        padding: 7px 8px;
    }
}

@media (max-width: 480px) {
    .cargo-form-container {
        margin: 50px 10px;
    }
    .card-header {
        font-size: 15px;
    }
    button.btn {
        font-size: 13px;
        padding: 6px 8px;
    }
    .form-group input {
        padding: 6px 8px;
    }
}
</style>
@endsection

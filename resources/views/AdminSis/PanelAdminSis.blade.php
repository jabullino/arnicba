@extends('layouts.app')

@section('content')
<div class="principal-admin bg-sky-900"><!---div principal--->
    <div class='card mx-auto'>

        <div class='card-header bg-sky-900 text-white bold text-center'>
            PANEL ADMINISTRADOR DE SISTEMA
            <br>
            @auth
            <div class="user-info mt-2">
                <h1>BIENVENIDO</h1>
                <h1>{{ auth()->user()->nombre }} {{ auth()->user()->apellido }}</h1>
                <p>{{ $cargoUsr }}</p>
            </div>
            @endauth
        </div>

        <div class="card-body bg-sky-900">
            @csrf
            <div class='admin-buttons'>
                <div class='button-item'>
                    <form action="{{ route('Usuarios.index') }}" method='get'>
                        <button type='submit' class='btn btn-primary'>Usuarios</button>
                    </form>
                </div>

                <div class='button-item'>
                    <form action="{{route('Cargos.index')}}" method='get'>
                        <button type='submit' class='btn btn-success'>Cargos</button>
                    </form>
                </div>

                <div class='button-item'>
                    <form action="{{route('Documentos.index')}}" method='get'>
                        <button type='submit' class='btn btn-info'>Documentos</button>
                    </form>
                </div>

                <div class='button-item'>
                    <form action="{{ route('logout') }}" method='get'>
                        <button type='submit' class='btn btn-danger'>Salir</button>
                    </form>
                </div>
            </div>
        </div><!--- fin div card-body---->
    </div><!---fin class card---->
</div><!---fin div principal---->
@endsection

@section('css')
<style>
.principal-admin {
    display: flex;
    justify-content: center;
    margin-top: 48px;
    padding: 1rem;
}

.card {
    width: 350px;
}

.admin-buttons {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
    justify-items: center;
}

.button-item button {
    width: 115px;
}

/* --- Media Queries --- */
@media (max-width: 768px) {
    .card {
        width: 90%;
    }
    .admin-buttons {
        grid-template-columns: 1fr;
        gap: 0.75rem;
    }
    .button-item button {
        width: 100%;
    }
}

@media (max-width: 480px) {
    .principal-admin {
        margin-top: 20px;
        padding: 0.5rem;
    }
    .card-header h1 {
        font-size: 1.2rem;
    }
    .card-header p {
        font-size: 0.9rem;
    }
}
</style>
@endsection

@extends('layouts.app')
@section('content')
<div class="cargos-container">

    <div class="card cargo-card">
        @if (session('CargoCreado'))
            <div class="alert alert-success text-center">
                {{ session('CargoCreado') }}
            </div>
        @endif

        @if (session('CargoEditado'))
            <div class="alert alert-warning text-center">
                {{ session('CargpEditado') }}
            </div>
        @endif 

        @if (session('CargoEliminado'))
            <div class="alert alert-danger text-center">
                {{ session('CargoEliminado') }}
            </div>
        @endif

        <div class="card-header">
            FORMULARIO PARA LA GESTIÓN DE CARGOS
        </div>

        <div class="card-actions">
            <form action="{{route('Cargos.create')}}" method="get">
                <button type="submit" class="btn btn-amber w-full">Registrar Cargo</button>
            </form>
        </div>

        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Cargo</th>
                        <th scope="col">Haber Básico</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @php $cont = 0; @endphp
                    @foreach ($cargos as $car)
                        @php $cont++; @endphp
                        <tr>
                            <th scope="row">{{ $cont }}</th>
                            <td>{{ $car->nombre }}</td>
                            <td>{{ $car->haberbasico }}</td>
                            <td class="acciones">
                                <form action="{{route('Cargos.edit',$car->id)}}" method="get" class="inline-form">
                                    @csrf
                                    <button type="submit" class="btn btn-green">Editar</button>
                                </form>
                                <form action="{{route('Cargos.destroy',$car->id)}}" method="post" class="inline-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-red">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div><!-- fin card-body -->

    </div><!-- fin card -->

</div><!-- fin cargos-container -->

{{-- ====== ESTILOS RESPONSIVE ====== --}}
<style>
.cargos-container {
    max-width: 1000px;
    margin: 20px auto;
    padding: 0 15px;
}

.card.cargo-card {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    padding: 15px;
}

.card-header {
    font-weight: bold;
    text-align: center;
    margin-bottom: 15px;
    font-size: 18px;
}

.card-actions {
    margin-bottom: 15px;
}

.btn {
    padding: 8px 12px;
    border-radius: 6px;
    color: #fff;
    border: none;
    cursor: pointer;
    font-size: 15px;
}

.btn-amber { background-color: #f59e0b; width: 100%; }
.btn-green { background-color: #16a34a; }
.btn-red { background-color: #dc2626; }

.alert {
    padding: 10px;
    border-radius: 6px;
    margin-bottom: 12px;
    font-weight: bold;
}

.alert-success { background-color: #16a34a; color: #fff; }
.alert-warning { background-color: #374151; color: #fff; }
.alert-danger { background-color: #dc2626; color: #fff; }

table.table {
    width: 100%;
    border-collapse: collapse;
    font-size: 15px;
}

table.table th,
table.table td {
    padding: 8px 10px;
    border: 1px solid #ddd;
    text-align: center;
}

table.table td.acciones {
    display: flex;
    justify-content: center;
    gap: 8px;
    flex-wrap: wrap;
}

.inline-form {
    display: inline-block;
}

/* ===== Media Queries ===== */
@media (max-width: 992px) {
    table.table th,
    table.table td {
        padding: 6px 8px;
        font-size: 14px;
    }

    .btn {
        font-size: 14px;
        padding: 6px 10px;
    }
}

@media (max-width: 768px) {
    .card-header {
        font-size: 16px;
    }

    table.table td.acciones {
        flex-direction: column;
    }

    .inline-form {
        width: 100%;
    }

    .inline-form button {
        width: 100%;
        margin-bottom: 6px;
    }
}

@media (max-width: 480px) {
    table.table th,
    table.table td {
        font-size: 13px;
        padding: 5px 6px;
    }

    .card-header {
        font-size: 15px;
    }

    .btn {
        font-size: 13px;
        padding: 5px 8px;
    }
}
</style>
@endsection

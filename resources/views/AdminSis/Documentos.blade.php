@extends('layouts.app')
@section('content')
<div class="documentos-container">
    <div class="card">
        {{-- Mensajes de sesión --}}
        @if (session('DocumentoCreado'))
            <div class="alert success-alert text-center">
                {{ session('DocumentoCreado') }}
            </div>
        @endif

        @if (session('DocumentoEditado'))
            <div class="alert info-alert text-center">
                {{ session('DocumentoEditado') }}
            </div>
        @endif 

        @if (session('DocumentoEliminado'))
            <div class="alert error-alert text-center">
                {{ session('DocumentoEliminado') }}
            </div>
        @endif

        <div class="card-header">
            FORMULARIO PARA LA GESTIÓN DE DOCUMENTOS
        </div>

        <div class="card-actions">
            <form action="{{ route('Documentos.create') }}" method="get">
                <button type="submit" class="btn-register w-full">Registrar Documento</button>
            </form>
        </div>

        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Documento</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($documentos as $doc)
                        @php $cont++; @endphp
                        <tr>
                            <th>{{ $cont }}</th>
                            <td>{{ $doc->nombre }}</td>
                            <td class="actions">
                                <form action="{{ route('Documentos.edit', $doc->id) }}" method="get" class="inline-form">
                                    @csrf
                                    <button type="submit" class="btn-edit">Editar</button>
                                </form>
                                <form action="{{ route('Documentos.destroy', $doc->id) }}" method="post" class="inline-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ====== ESTILOS RESPONSIVE ====== --}}
<style>
.documentos-container {
    max-width: 900px;
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

.card-actions {
    margin-bottom: 15px;
}

.btn-register {
    background-color: #f59e0b;
    color: white;
    font-weight: bold;
    padding: 10px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}

.table {
    width: 100%;
    border-collapse: collapse;
}

.table th, .table td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: center;
}

.table th {
    background-color: #f3f4f6;
}

.actions {
    display: flex;
    justify-content: center;
    gap: 5px;
}

.inline-form {
    display: inline-block;
    margin: 0;
}

.btn-edit {
    background-color: #16a34a;
    color: white;
    padding: 5px 10px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.btn-delete {
    background-color: #dc2626;
    color: white;
    padding: 5px 10px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

/* Alertas */
.alert {
    padding: 10px;
    margin-bottom: 10px;
    border-radius: 6px;
    font-weight: bold;
}

.success-alert { background-color: #16a34a; color: white; }
.info-alert { background-color: #4b5563; color: white; }
.error-alert { background-color: #dc2626; color: white; }

/* ===== Media Queries ===== */
@media (max-width: 1024px) {
    .table th, .table td {
        font-size: 14px;
        padding: 6px;
    }
    .btn-register, .btn-edit, .btn-delete {
        font-size: 14px;
        padding: 8px;
    }
}

@media (max-width: 480px) {
    .documentos-container {
        margin: 30px 10px;
    }
    .table th, .table td {
        font-size: 12px;
        padding: 5px;
    }
    .btn-register, .btn-edit, .btn-delete {
        font-size: 12px;
        padding: 6px;
    }
    .actions {
        flex-direction: column;
        gap: 5px;
    }
}
</style>
@endsection

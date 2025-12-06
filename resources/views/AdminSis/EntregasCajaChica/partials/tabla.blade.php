@extends('layouts.app')

@section('page_header')
    <h1>Entregas de Caja Chica</h1>
@stop

@section('page_content')

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            {{-- Aquí puedes colocar filtros si quieres --}}
        </div>
        <div>
            <a href="{{ route('entregascajachicas.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> Nueva Entrega
            </a>
        </div>
    </div>

    <div class="card-body">
        <table class="table table-striped table-bordered" id="tabla-entregas">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Fecha de Entrega</th>
                    <th>Monto</th>
                    <th>Mes</th>
                    <th>Caja Chica</th>
                    <th >Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Aquí se cargan los registros por JS -->
            </tbody>
        </table>
    </div>
</div>

{{-- ======== ESTILOS RESPONSIVE SIN BOOTSTRAP ======== --}}
<style>
/* ====== ESTILOS BASE ====== */
.card {
    max-width: 1000px;
    margin: 0 auto;
    border-radius: 10px;
    overflow-x: auto;
}

.table {
    width: 100%;
    border-collapse: collapse;
    text-align: center;
    font-size: 0.95rem;
}

.table th,
.table td {
    padding: 10px;
    border: 1px solid #ddd;
    word-wrap: break-word;
}

/* ====== PANTALLAS MEDIANAS ====== */
@media (max-width: 992px) {
    .card {
        width: 95%;
        margin: 10px auto;
    }

    .btn {
        font-size: 0.9rem;
        padding: 6px 12px;
    }

    .table th,
    .table td {
        font-size: 0.9rem;
        padding: 8px;
    }
}

/* ====== PANTALLAS PEQUEÑAS (CELULARES) ====== */
@media (max-width: 768px) {
    .card-header {
        flex-direction: column;
        text-align: center;
        gap: 10px;
    }

    .table thead {
        display: none; /* Oculta encabezados */
    }

    .table, 
    .table tbody, 
    .table tr, 
    .table td {
        display: block;
        width: 100%;
    }

    .table tr {
        margin-bottom: 12px;
        border: 1px solid #ccc;
        border-radius: 8px;
        padding: 10px;
        background-color: #fafafa;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .table td {
        text-align: right;
        padding: 6px 10px;
        border: none;
        border-bottom: 1px solid #eee;
        position: relative;
        font-size: 0.9rem;
    }

    .table td:last-child {
        border-bottom: none;
    }

    .table td::before {
        content: attr(data-label);
        position: absolute;
        left: 10px;
        font-weight: bold;
        text-transform: uppercase;
        color: #333;
        text-align: left;
    }

    .btn {
        width: 100%;
        font-size: 0.95rem;
        padding: 8px;
    }
}

/* ====== PANTALLAS MUY PEQUEÑAS ====== */
@media (max-width: 480px) {
    h1 {
        font-size: 1.1rem;
        text-align: center;
    }

    .card {
        padding: 6px;
    }

    .table td {
        font-size: 0.85rem;
    }

    .btn {
        padding: 8px;
        font-size: 0.9rem;
    }
}
</style>

{{-- ======== SCRIPT OPCIONAL PARA ETIQUETAS AUTOMÁTICAS ======== --}}
<script>
// Este script agrega automáticamente data-label a las celdas según los encabezados.
// No altera la funcionalidad, solo mejora la visualización móvil.
document.addEventListener("DOMContentLoaded", function() {
    const table = document.getElementById("tabla-entregas");
    const headers = Array.from(table.querySelectorAll("thead th"));
    const rows = table.querySelectorAll("tbody tr");

    rows.forEach(row => {
        Array.from(row.children).forEach((cell, i) => {
            if (headers[i]) {
                cell.setAttribute("data-label", headers[i].innerText);
            }
        });
    });
});
</script>

@endsection

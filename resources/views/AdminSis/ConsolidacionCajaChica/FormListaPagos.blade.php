@extends('layouts.app')

@section('page_header')
    <h1>Consolidar Pagos de Caja Chica</h1>
@stop

@section('page_content')
    {{-- Mensajes SweetAlert --}}
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: "{{ session('success') }}",
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: '¡Error!',
                text: "{{ session('error') }}",
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false
            });
        </script>
    @endif

<form action="{{route('consolidarcajachica.store')}}" method='POST'>
    @csrf
    <div class="card">
        <div class="card-header text-center font-bold">
            FORMULARIO DE PAGOS REALIZADOS
        </div>
        <div class="card-body">
            
        </div>
       <button type="submit" class="btn btn-success w-full">Consolidar Caja Chica</button>
    </div>

    {{-- Filtros de año y mes --}}
    <div id="filtros" style='width:900px;margin-left:80px'>
    <div class="mb-3 mt-3">
        <label for="anio">Año:</label>
        <select id="anio" name='anio'class="form-control">
            @for ($i = date('Y') - 5; $i <= date('Y'); $i++)
                <option value="{{ $i }}" @if ($i == date('Y')) selected @endif>
                    {{ $i }}
                </option>
            @endfor
        </select>
    </div>

    <div class="mb-3">
        <label for="mes">Mes:</label>
        <select id="mes" name='mes' class="form-control">
            <option value="">Todos</option>
            @foreach ([1 => 'Enero',2 => 'Febrero',3 => 'Marzo',4 => 'Abril',5 => 'Mayo',
                       6 => 'Junio',7 => 'Julio',8 => 'Agosto',9 => 'Septiembre',10 => 'Octubre',
                       11 => 'Noviembre',12 => 'Diciembre'] as $num => $nombre)
                <option value="{{ $num }}" @if ($num == date('n')) selected @endif>
                    {{ $nombre }}
                </option>
            @endforeach
        </select>
    </div>
    </div><!---fin div filtros --->

    {{-- Tabla de gastos --}}
    <div class="card mt-3">
        <div class="card-body">
            <div id="resultado-gastos" data-ajax-url="{{ route('gastoscajachica.ajax') }}">
                <table id="tablaGastos" class="table table-bordered table-striped text-center align-middle">
                    <thead class="bg-gray-700 text-white">
                        <tr>
                            <th>Fecha</th>
                            <th>Documento</th>
                            <th>Cuenta</th>
                            <th>Subcuenta</th>
                            <th>Importe</th>
                            <th>Seleccionar/Todos <input type="checkbox" name="todos" id="todos"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="6" class="text-center text-gray-500">
                                Selecciona un año y mes para mostrar los pagos.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop
</form>

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const anioSelect = document.getElementById('anio');
    const mesSelect = document.getElementById('mes');
    const tbody = document.querySelector('#tablaGastos tbody');
    const ajaxUrl = "{{ route('pagoscajachica.ajax') }}";

    function cargarGastos() {
        const anio = anioSelect.value;
        const mes = mesSelect.value;

        // Solo cargar si hay año y mes seleccionados
        if (!anio || !mes) {
            tbody.innerHTML = `<tr>
                <td colspan="6" class="text-center text-gray-500">
                    Selecciona un año y mes para mostrar los pagos.
                </td>
            </tr>`;
            return;
        }

        // Mostrar loader temporal
        tbody.innerHTML = `<tr>
            <td colspan="6" class="text-center text-gray-500">Cargando...</td>
        </tr>`;

        // Llamada AJAX
        fetch(`${ajaxUrl}?anio=${anio}&mes=${mes}`)
            .then(res => {
                if (!res.ok) throw new Error("Error en la respuesta");
                return res.json();
            })
            .then(data => {
                tbody.innerHTML = data.html;
            })
            .catch(err => {
                console.error(err);
                Swal.fire('Error', 'Ocurrió un error al cargar los datos.', 'error');
                tbody.innerHTML = `<tr>
                    <td colspan="6" class="text-center text-gray-500">
                        No se pudieron cargar los pagos.
                    </td>
                </tr>`;
            });
    }

    // Escuchar cambios en año y mes
    anioSelect.addEventListener('change', cargarGastos);
    mesSelect.addEventListener('change', cargarGastos);
});
</script>

<script>
document.addEventListener('change', function (e) {
    if (e.target.id === 'todos') {
        const checkboxes = document.querySelectorAll('input[name="escogidos[]"]');
        checkboxes.forEach(chk => chk.checked = e.target.checked);
    }
});
</script>

{{-- MEDIA QUERIES RESPONSIVE --}}
<style>
/* ====== ESTILOS BASE ====== */
.card {
    max-width: 900px;
    margin: 0 auto;
    border-radius: 10px;
}

.table {
    width: 100%;
    border-collapse: collapse;
    word-wrap: break-word;
}

.table th, .table td {
    padding: 8px;
    font-size: 0.95rem;
}

/* ====== RESPONSIVE ====== */

/* Pantallas medianas (tablets) */
@media (max-width: 992px) {
    .card {
        width: 95%;
        margin: 10px auto;
    }

    table thead {
        font-size: 0.9rem;
    }

    .form-control, .btn {
        width: 100%;
        font-size: 0.95rem;
    }
}

/* Pantallas pequeñas (celulares) */
@media (max-width: 768px) {
    .card-header {
        font-size: 1rem;
    }

    .table thead {
        display: none; /* Oculta encabezado */
    }

    .table tbody tr {
        display: block;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 8px;
        padding: 10px;
        background: #fafafa;
        text-align: left;
    }

    .table td {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.9rem;
        border: none;
        border-bottom: 1px solid #e5e5e5;
    }

    .table td::before {
        content: attr(data-label);
        font-weight: bold;
        text-transform: uppercase;
        color: #333;
    }

    .btn {
        width: 100%;
        padding: 10px;
        font-size: 1rem;
    }
}

/* Pantallas muy pequeñas */
@media (max-width: 480px) {
    h1 {
        font-size: 1.2rem;
        text-align: center;
    }

    .card {
        padding: 8px;
    }

    select, label {
        width: 100%;
        display: block;
        margin-bottom: 8px;
    }

    .table td {
        font-size: 0.85rem;
    }
    
}
</style>
@stop

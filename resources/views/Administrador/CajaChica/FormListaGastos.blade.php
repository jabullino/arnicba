@extends('layouts.app')
<div id='principal' style=''>

@section('page_header')
    <h1>Gastos Caja Chica</h1>
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

    <div class="card" style='margin-top:50px !important;'>
        <div class="card-header text-center text-white font-bold " style='background-color:#3a3939'>
            FORMULARIO DE PAGOS REALIZADOS
        </div>
        <div class="card-body" style='height:100px'>
            <div id='saldo' class='form-group w-48' style='float:right'>
                <label for="saldo">Saldo Disponible</label>
                <input type="text" id='saldo' name='saldo'
                       class='form-control text-right text-black font-bold text-basic'
                       value="{{ number_format($disponible, 2, '.', '') }}" readonly>
            </div>
        </div >
        <a href="{{ route('gastoscajachica.create') }}" class="btn btn-success w-full" style='background-color:#0f142b !important;'>Crear Nuevo Pago</a>
    </div>

    {{-- Filtros de año y mes --}}
    <div class='filtros'>
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
     </div><!--fin div filtros --->

    {{-- Tabla de gastos --}}
    <div class="card mt-3">
        <div class="card-body">
            <div id="resultado-gastos" data-ajax-url="{{ route('gastoscajachica.ajax') }}">
                <table id="tablaGastos" class="table table-bordered table-striped text-center align-middle table-gastos">
                    <thead class="bg-gray-700 text-white">
                        <tr>
                            <th>Fecha</th>
                            <th>Documento</th>
                            <th>Cuenta</th>
                            <th>Subcuenta</th>
                            <th>Importe</th>
                            <th>Acciones</th>
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
</div><!--div principal --->

@section('css')
<style>
/* ==== Colores oficiales del modo oscuro de AdminLTE ==== */

/* Fondo global */
body, .wrapper, .content-wrapper {
    background-color: #343a40 !important;
    color: #f8f9fa !important;
}

/* Navbar */
.main-header {
    background-color: #212529 !important;
    color: #f8f9fa !important;
    border-bottom: 1px solid #495057 !important;
}

/* Sidebar */
.main-sidebar {
    background-color: #23272b !important;
    color: #f8f9fa !important;
}

/* Logo / marca */
.brand-link {
    background-color: #1d2124 !important;
    color: #f8f9fa !important;
}

/* Links sidebar */
.nav-link {
    color: #c2c7d0 !important;
}
.nav-link.active, .nav-link:hover {
    background-color: #495057 !important;
    color: #fff !important;
}

/* Cards */
.card {
    background-color: #2f353a !important;
    color: #fff !important;
    border: 1px solid #495057 !important;
}

/* Inputs y selects */
.form-control {
    background-color: #3b4045 !important;
    color: #fff !important;
    border: 1px solid #555b61 !important;
}

/* Labels */
label {
    color: #f8f9fa !important;
}

/* Botones globales */
button, .btn {
    background-color: #495057 !important;
    color: #fff !important;
    border: none !important;
}
button:hover, .btn:hover {
    background-color: #6c757d !important;
}

/* SweetAlert */
.swal2-popup {
    background-color: #2f353a !important;
    color: #fff !important;
}

/* ==== Colores botones tabla de gastos ==== */
#resultado-gastos table#tablaGastos .btn-info {
    background-color: #0dcaf0 !important;
    color: #fff !important;
    border: none !important;
}
#resultado-gastos table#tablaGastos .btn-info:hover {
    background-color: #31d2f2 !important;
}

#resultado-gastos table#tablaGastos .btn-warning {
    background-color: #ffc107 !important;
    color: #212529 !important;
    border: none !important;
}
#resultado-gastos table#tablaGastos .btn-warning:hover {
    background-color: #e0a800 !important;
}

#resultado-gastos table#tablaGastos .btn-danger {
    background-color: #dc3545 !important;
    color: #fff !important;
    border: none !important;
}
#resultado-gastos table#tablaGastos .btn-danger:hover {
    background-color: #bb2d3b !important;
}

/* Media Queries */
@media (max-width: 768px) {
    .card {
        width: 95%;
        margin: 0 auto 1rem auto;
    }

    #saldo {
        width: 100% !important;
        float: none !important;
        margin-bottom: 1rem;
    }

    #anio, #mes {
        width: 100% !important;
    }

    #tablaGastos {
        display: block;
        overflow-x: auto;
        width: 100%;
    }

    #tablaGastos th, #tablaGastos td {
        white-space: nowrap;
    }
}
</style>
@stop

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const anioSelect = document.getElementById('anio');
    const mesSelect = document.getElementById('mes');
    const tbody = document.querySelector('#tablaGastos tbody');
    const ajaxUrl = "{{ route('gastoscajachica.ajax') }}";

    function cargarGastos() {
        const anio = anioSelect.value;
        const mes = mesSelect.value;

        if (!anio || !mes) {
            tbody.innerHTML = `<tr>
                <td colspan="6" class="text-center text-gray-500">
                    Selecciona un año y mes para mostrar los pagos.
                </td>
            </tr>`;
            return;
        }

        tbody.innerHTML = `<tr>
            <td colspan="6" class="text-center text-gray-500">Cargando...</td>
        </tr>`;

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

    anioSelect.addEventListener('change', cargarGastos);
    mesSelect.addEventListener('change', cargarGastos);
});
</script>
@stop

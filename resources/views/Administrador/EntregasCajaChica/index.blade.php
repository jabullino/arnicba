@extends('layouts.app')

@section('page_header')
    <h1>Entregas Caja Chica</h1>
@stop

@section('page_content')
@if(session('success'))
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

@if(session('error'))
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

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Filtros</h3>
    </div>
    <div class="card-body">
        <div class="mb-3 mt-3">
            <label for="anio">Año:</label>
            <select id="anio" class="form-control">
                @for ($i = date('Y') - 5; $i <= date('Y'); $i++)
                    <option value="{{ $i }}" @if ($i == date('Y')) selected @endif>
                        {{ $i }}
                    </option>
                @endfor
            </select>
        </div>

        <div class="mb-3">
            <label for="mes">Mes:</label>
            <select id="mes" class="form-control">
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
    </div>
</div>

<div class="card mt-3">
    <div class="card-body">
        <div id="resultado-entregas" data-ajax-url="{{ route('listaEntregascajachica.lista') }}">
            <table id="tablaEntregas">
                <thead>
                    <!-- Headers generados dinámicamente -->
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ====== ESTILOS RESPONSIVE ====== --}}
<style>
.card {
    max-width: 1000px;
    margin: 20px auto;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    background-color: #fff;
}

.card-header {
    margin-bottom: 15px;
}

label {
    font-weight: 600;
    margin-bottom: 5px;
    display: block;
}

input, select, button {
    width: 100%;
    padding: 8px 10px;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-size: 15px;
    box-sizing: border-box;
    margin-bottom: 10px;
}

button.btn {
    cursor: pointer;
}

table {
    width: 100%;
    border-collapse: collapse;
}

table th, table td {
    border: 1px solid #ccc;
    padding: 8px;
    text-align: center;
}

/* ====== Media queries ====== */
@media (max-width: 992px) {
    .card {
        width: 95%;
        padding: 10px;
    }
    input, select, button {
        font-size: 14px;
        padding: 7px;
    }
}

@media (max-width: 768px) {
    h3.card-title {
        text-align: center;
    }

    table, thead, tbody, th, td, tr {
        display: block;
    }

    thead tr {
        display: none;
    }

    tbody tr {
        margin-bottom: 12px;
        border: 1px solid #ccc;
        border-radius: 8px;
        padding: 10px;
        background: #fafafa;
    }

    tbody td {
        text-align: right;
        padding: 6px 10px;
        position: relative;
        border: none;
        border-bottom: 1px solid #eee;
    }

    tbody td::before {
        content: attr(data-label);
        position: absolute;
        left: 10px;
        font-weight: bold;
        text-transform: uppercase;
        color: #333;
        text-align: left;
    }

    button.btn {
        font-size: 14px;
        padding: 8px;
    }
}

@media (max-width: 480px) {
    .card {
        padding: 8px;
        margin: 10px;
    }

    input, select, button {
        font-size: 13px;
        padding: 6px;
    }

    h1 {
        font-size: 18px;
        text-align: center;
    }
}
</style>

@stop

@section('js')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function() {

    function cargarEntregas() {
        const ajaxUrl = $('#resultado-entregas').data('ajax-url');
        const anio = $('#anio').val();
        const mes = $('#mes').val();

        $.ajax({
            url: ajaxUrl,
            type: 'GET',
            data: { anio, mes },
            success: function(response) {
                $('#resultado-entregas').html('');

                const entregas = response.entregas || [];
                const total = response.total || 0;

                if (entregas.length === 0) {
                    $('#resultado-entregas').html('<p>No hay entregas para este mes/año.</p>');
                    return;
                }

                let tabla = '<table class="table table-striped table-bordered">';
                tabla += '<thead><tr><th>Fecha de Entrega</th><th>Monto</th></tr></thead><tbody>';

                $.each(entregas, function(i, entrega) {
                    let fecha = new Date(entrega.fecha_entrega);
                    let dia = ('0' + fecha.getDate()).slice(-2);
                    let mes = ('0' + (fecha.getMonth()+1)).slice(-2);
                    let anio = fecha.getFullYear();
                    let fechaFormateada = dia + '-' + mes + '-' + anio;

                    tabla += '<tr>';
                    tabla += '<td data-label="Fecha de Entrega">' + fechaFormateada + '</td>';
                    tabla += '<td data-label="Monto">' + entrega.monto + '</td>';
                    tabla += '</tr>';
                });

                // 👇 Fila TOTAL al final
                tabla += '<tr>';
                tabla += '<td style="font-weight:bold;">TOTAL</td>';
                tabla += '<td style="font-weight:bold;">' + total.toFixed(2) + '</td>';
                tabla += '</tr>';

                tabla += '</tbody></table>';
                $('#resultado-entregas').html(tabla);
            },
            error: function(xhr, status, error) {
                console.error('Error al cargar entregas:', xhr, status, error);
                $('#resultado-entregas').html('<p>Error al cargar entregas.</p>');
            }
        });
    }

    cargarEntregas();

    $('#anio, #mes').on('change', function() { cargarEntregas(); });

    $(document).on('click', '.eliminar-entrega', function() {
        const url = $(this).data('url');
        if (confirm('¿Seguro que deseas eliminar esta entrega?')) {
            $.ajax({
                url: url,
                type: 'DELETE',
                data: { _token: $('meta[name="csrf-token"]').attr('content') },
                success: function() { cargarEntregas(); },
                error: function() { alert('No se pudo eliminar la entrega.'); }
            });
        }
    });

});
</script>
@stop


@extends('layouts.app')

@section('content')

<style>
@media print {
    body {
        background: white !important;
        color: black !important;
    }

    button,
    .no-print {
        display: none !important;
    }

    .bg-gray-800,
    .bg-gray-700 {
        background: white !important;
        color: black !important;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    table th,
    table td {
        border: 1px solid #000;
        padding: 6px;
        font-size: 12px;
    }

    label {
        font-weight: bold;
    }
}
</style>

<div class="w-full px-2 sm:px-6 py-6">

    <!-- HEADER -->
    <div class="bg-gray-800 text-white p-4 rounded-lg mb-6 
        grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 text-base sm:text-lg">

        <!-- Gestión -->
        <div class="flex flex-col">
            <label class="mb-1">Gestión</label>
            <input type="text" class="w-full bg-gray-700 rounded px-3 py-2" readonly
                value="{{ $gestion->nombre }}">
        </div>

        <!-- Código -->
        <div class="flex flex-col">
            <label class="mb-1">ID Permiso</label>
            <input type="text" class="w-full bg-gray-700 rounded px-3 py-2" readonly
                value="{{ $codigo }}">
        </div>

        <!-- Fecha visible -->
        <div class="flex flex-col">
            <label class="mb-1">Fecha</label>
            <input type="date" id="fecha_visible"
                class="w-full bg-gray-700 rounded px-3 py-2">
        </div>

        <!-- Estado + imprimir -->
        <div class="flex flex-col justify-end col-span-1 sm:col-span-2 lg:col-span-2">
            <label class="mb-1">Estado</label>

            <div class="flex justify-between items-center">

                <button disabled
                    class="px-4 py-2 rounded text-white text-sm 
                    {{ isset($estado) && $estado == 'autorizado' ? 'bg-green-600' : 'bg-yellow-600' }}">
                    {{ isset($estado) && $estado == 'autorizado' ? 'Autorizado' : 'Pendiente' }}
                </button>

                <button disabled
                    style="background-color:#facc15; color:#000; font-weight:bold;"
                    class="px-4 py-2 rounded text-sm cursor-not-allowed">
                    🖨️ Imprimir
                </button>

            </div>
        </div>

    </div>

    <form method="POST" action="{{ route('administrador.solicitudes.store') }}">
        @csrf

        <!-- 🔥 CAMPOS IMPORTANTES -->
        <input type="hidden" name="gestion_id" value="{{ $gestion->id }}">
        <input type="hidden" name="fecha" id="fecha_hidden">

        <div class="bg-gray-800 p-4 rounded-lg overflow-x-auto">
            <table class="w-full text-white text-base sm:text-lg" id="tabla">
                <thead>
                    <tr class="border-b border-gray-600 text-left">
                        <th class="py-3 px-2">#</th>
                        <th class="py-3 px-2">Descripción</th>
                        <th class="py-3 px-2">Cantidad</th>
                        <th class="py-3 px-2 text-center no-print">Acciones</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>

            <button type="button" onclick="agregarFila()"
                class="mt-4 bg-blue-600 hover:bg-blue-500 px-5 py-2 rounded text-white font-semibold w-full no-print">
                + Agregar
            </button>
        </div>

        <button
            style="background-color:#16a34a; color:white; font-weight:600;"
            class="mt-6 px-6 py-3 rounded text-base sm:text-lg w-full no-print">
            Guardar
        </button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
let contador = 0;

/* SINCRONIZAR FECHA */
document.addEventListener('DOMContentLoaded', function () {
    const visible = document.getElementById('fecha_visible');
    const hidden = document.getElementById('fecha_hidden');

    // valor inicial hoy
    let hoy = new Date().toISOString().split('T')[0];
    visible.value = hoy;
    hidden.value = hoy;

    visible.addEventListener('change', function () {
        hidden.value = this.value;
    });
});

/* TOTAL */
function crearFilaTotal() {
    if (!document.getElementById('fila-total')) {
        let fila = `
        <tr id="fila-total" class="border-t border-gray-500 font-bold">
            <td colspan="2" class="text-right px-2 py-3">TOTAL:</td>
            <td class="px-2 py-3">
                <input type="text" id="total"
                    class="w-full bg-gray-900 text-white px-3 py-2 rounded text-right"
                    readonly value="0.00">
            </td>
            <td></td>
        </tr>`;
        document.querySelector('#tabla tbody').insertAdjacentHTML('beforeend', fila);
    }
}

function calcularTotal() {
    let total = 0;

    document.querySelectorAll('.cantidad').forEach(input => {
        let valor = parseFloat(input.value);
        if (!isNaN(valor)) total += valor;
    });

    document.getElementById('total').value = total.toFixed(2);
}

/* FILAS */
function agregarFila() {
    contador++;

    let fila = `
    <tr class="border-b border-gray-700">
        <td class="px-2 py-3">${contador}</td>

        <td class="px-2 py-3">
            <input type="text" name="detalles[${contador}][descripcion]"
                class="w-full bg-gray-700 text-white px-3 py-2 rounded descripcion">
        </td>

        <td class="px-2 py-3">
            <input type="text" name="detalles[${contador}][cantidad]"
                class="w-full bg-gray-700 text-white px-3 py-2 rounded cantidad text-right">
        </td>

        <td class="px-2 py-3 no-print">
            <div class="flex gap-2 justify-center">

                <button type="button" onclick="eliminarFila(this)"
                    class="bg-red-600 px-3 py-1 rounded text-white font-bold">
                    X
                </button>

                <button type="button" onclick="agregarFila()"
                    class="bg-green-500 px-3 py-1 rounded text-white font-bold">
                    +
                </button>

            </div>
        </td>
    </tr>`;

    let tbody = document.querySelector('#tabla tbody');
    let totalRow = document.getElementById('fila-total');

    if (totalRow) {
        totalRow.insertAdjacentHTML('beforebegin', fila);
    } else {
        tbody.insertAdjacentHTML('beforeend', fila);
    }

    crearFilaTotal();
}

/* ELIMINAR */
function eliminarFila(btn) {
    Swal.fire({
        title: '¿Eliminar fila?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí',
    }).then((result) => {
        if (result.isConfirmed) {
            btn.closest('tr').remove();
            calcularTotal();
        }
    });
}

/* VALIDACIÓN */
document.querySelector('form').addEventListener('submit', function(e) {

    let filas = document.querySelectorAll('.cantidad');

    if (filas.length === 0) {
        e.preventDefault();
        Swal.fire('Debes agregar al menos una fila');
        return;
    }

    let valido = true;
    filas.forEach(i => { if (!i.value) valido = false; });

    if (!valido) {
        e.preventDefault();
        Swal.fire('Completa todas las cantidades');
    }
});

/* INPUTS */
document.addEventListener('input', function(e) {

    if (e.target.classList.contains('descripcion')) {
        e.target.value = e.target.value.toUpperCase();
    }

    if (e.target.classList.contains('cantidad')) {
        e.target.value = e.target.value.replace(/[^0-9.]/g, '');
        calcularTotal();
    }
});
</script>

@endsection
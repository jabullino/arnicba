@extends('layouts.app')

@section('content')
<div class="container mx-auto px-2 sm:px-4 py-6 text-white">

    <h1 class="text-2xl sm:text-3xl mb-6 font-semibold">
        Autorizar Solicitud Caja Chica
    </h1>

    <!-- 🔹 DATOS GENERALES -->
    <div class="bg-gray-800 p-5 rounded-lg mb-6 space-y-2 text-base sm:text-lg">
        <p><strong>ID:</strong> {{ $solicitud->id }}</p>
        <p><strong>Código:</strong> {{ $solicitud->codigo }}</p>
        <p><strong>Gestión:</strong> {{ $solicitud->gestion->nombre ?? '' }}</p>
        <p><strong>Fecha:</strong> {{ $solicitud->fecha }}</p>
        <p>
            <strong>Estado:</strong> 
            <span class="bg-yellow-600 px-3 py-1 rounded">
                {{ $solicitud->estado }}
            </span>
        </p>
    </div>

    <!-- 🔹 FORMULARIO -->
    <form method="POST" action="{{ route('adminsis.solicitudes.update', $solicitud->id) }}">
        @csrf
        @method('PUT')

        <!-- 🔹 DETALLE -->
        <div class="bg-gray-800 p-5 rounded-lg overflow-x-auto">
            <table class="min-w-full text-base sm:text-lg">
                <thead>
                    <tr class="border-b border-gray-600 text-left">
                        <th class="px-3 py-3">#</th>
                        <th class="px-3 py-3">Descripción</th>
                        <th class="px-3 py-3 text-right">Cantidad</th>
                        <th class="px-3 py-3 text-center">No aceptar</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($solicitud->detalles as $i => $detalle)
                    <tr class="border-b border-gray-700">
                        <td class="px-3 py-3">{{ $i + 1 }}</td>

                        <td class="px-3 py-3">
                            {{ $detalle->descripcion }}
                        </td>

                        <!-- 🔥 CANTIDAD A LA DERECHA -->
                        <td class="px-3 py-3 text-right cantidad">
                            {{ $detalle->cantidad }}
                        </td>

                        <!-- 🔥 CHECKBOX -->
                        <td class="px-3 py-3 text-center">
                            <input 
                                type="checkbox" 
                                name="rechazados[]" 
                                value="{{ $detalle->id }}"
                                class="w-5 h-5 cursor-pointer check-item"
                                data-cantidad="{{ $detalle->cantidad }}"
                            >
                        </td>
                    </tr>
                    @endforeach
                </tbody>

                <!-- 🔥 TOTAL -->
                <tfoot>
                    <tr class="border-t border-gray-500 font-semibold">
                        <td colspan="2" class="px-3 py-3 text-right">
                            TOTAL:
                        </td>
                        <td class="px-3 py-3 text-right" id="total">
                            0
                        </td>
                        <td></td>
                    </tr>
                </tfoot>

            </table>
        </div>

        <!-- 🔹 BOTONES -->
        <div class="mt-6 flex gap-4">

            <button 
                type="submit"
                class="bg-green-600 hover:bg-green-700 px-6 py-2 rounded text-white font-semibold">
                Autorizar
            </button>

            <a href="{{ url()->previous() }}"
               class="bg-gray-600 hover:bg-gray-700 px-6 py-2 rounded text-white">
                Cancelar
            </a>

        </div>

    </form>

</div>

<!-- 🔥 SCRIPT TOTAL DINÁMICO -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    const checkboxes = document.querySelectorAll('.check-item');
    const totalElement = document.getElementById('total');

    let total = 0;

    // 🔹 calcular total inicial
    checkboxes.forEach(cb => {
        total += parseFloat(cb.dataset.cantidad);
    });

    totalElement.textContent = total.toFixed(2);

    // 🔹 actualizar al marcar/desmarcar
    checkboxes.forEach(cb => {
        cb.addEventListener('change', function () {

            let valor = parseFloat(this.dataset.cantidad);

            if (this.checked) {
                total -= valor;
            } else {
                total += valor;
            }

            totalElement.textContent = total.toFixed(2);
        });
    });

});
</script>

@endsection
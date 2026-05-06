@extends('layouts.app')

@section('content')

    <div class="container mx-auto px-4 py-6 w-full">

        <!-- TITULO -->
        <h1 class="text-white text-3xl sm:text-4xl mb-8 font-bold">
            Solicitudes Caja Chica
        </h1>

        <!-- TABLA -->
        <div class="bg-gray-800 p-6 rounded-xl overflow-x-auto w-full shadow-lg">

            <table class="w-full table-auto text-white text-lg sm:text-xl">

                <!-- CABECERA -->
                <thead>
                    <tr class="border-b border-gray-600 text-left">

                        <th class="px-5 py-4">
                            ID
                        </th>

                        <th class="px-5 py-4">
                            Código
                        </th>

                        <th class="px-5 py-4">
                            Gestión
                        </th>

                        <th class="px-5 py-4">
                            Fecha
                        </th>

                        <th class="px-5 py-4">
                            Estado
                        </th>

                        @if (isset($modo) && ($modo === 'adminsis' || $modo === 'admin'))
                            <th class="px-5 py-4">
                                Acción
                            </th>
                        @endif

                    </tr>
                </thead>

                <!-- CUERPO -->
                <tbody>

                    @foreach ($solicitudes as $solicitud)
                        <tr class="border-b border-gray-700 hover:bg-gray-700 cursor-pointer transition duration-200"
                            onclick="mostrarDetalle({{ $solicitud->id }})">

                            <!-- ID -->
                            <td class="px-5 py-4">
                                {{ $solicitud->id }}
                            </td>

                            <!-- CODIGO -->
                            <td class="px-5 py-4">
                                {{ $solicitud->codigo }}
                            </td>

                            <!-- GESTION -->
                            <td class="px-5 py-4">
                                {{ $solicitud->gestion->nombre ?? '' }}
                            </td>

                            <!-- FECHA -->
                            <td class="px-5 py-4">
                                {{ $solicitud->fecha }}
                            </td>

                            <!-- ESTADO -->
                            <td class="px-5 py-4">

                                @if ($solicitud->estado === 'autorizado')
                                    <span class="bg-green-600 px-4 py-2 rounded-lg text-base sm:text-lg">
                                        {{ $solicitud->estado }}
                                    </span>
                                @else
                                    <span class="bg-yellow-600 px-4 py-2 rounded-lg text-base sm:text-lg">
                                        {{ $solicitud->estado }}
                                    </span>
                                @endif

                            </td>

                            <!-- ACCIONES -->
                            @if (isset($modo) && ($modo === 'adminsis' || $modo === 'admin'))
                                <td class="px-5 py-4" onclick="event.stopPropagation()">

                                    <div class="flex flex-wrap gap-3">

                                        @if ($solicitud->estado === 'autorizado')
                                            <!-- AUTORIZADO -->
                                            <button
                                                class="bg-green-600 text-white px-4 py-2 rounded-lg text-base sm:text-lg opacity-100" style='background-color:green'>
                                                Autorizado
                                            </button>

                                            @if (is_null($solicitud->impreso))
                                                <!-- IMPRIMIR -->
                                                <a href="{{ url('Administrador/Administrador/solicitudes/' . $solicitud->id . '/imprimir') }}"
                                                    target="_blank"
                                                    class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg text-base sm:text-lg transition duration-200">
                                                    Imprimir
                                                </a>
                                            @else
                                                <!-- IMPRESO -->
                                                <button
                                                    class="bg-gray-600 px-4 py-2 rounded-lg text-base sm:text-lg cursor-not-allowed opacity-80"
                                                    disabled>
                                                    Impreso
                                                </button>
                                            @endif
                                        @else
                                            <!-- AUTORIZAR -->
                                            <a href="{{ route('adminsis.solicitudes.edit', $solicitud->id) }}"
                                                class="bg-green-600 hover:bg-green-700 px-4 py-2 rounded-lg text-base sm:text-lg transition duration-200">
                                                Autorizar
                                            </a>
                                        @endif

                                    </div>

                                </td>
                            @endif

                        </tr>
                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

    <!-- MODAL -->
    <div id="modal" class="fixed inset-0 bg-black bg-opacity-60 hidden items-center justify-center z-50">

        <div class="bg-gray-900 w-full max-w-5xl rounded-xl p-8 relative mx-2 shadow-2xl">

            <!-- BOTON CERRAR -->
            <button onclick="cerrarModal()"
                class="absolute top-3 right-4 text-white text-3xl hover:text-red-400 transition">
                ✕
            </button>

            <!-- TITULO -->
            <h2 class="text-white text-2xl sm:text-3xl mb-6 font-bold">
                Detalle Solicitud
            </h2>

            <!-- CONTENIDO -->
            <div id="detalleContenido" class="text-white text-lg sm:text-xl"></div>

        </div>

    </div>

    <script>
        let solicitudes = @json($solicitudes);

        function mostrarDetalle(id) {
            let solicitud = solicitudes.find(s => s.id === id);

            let html = `

        <!-- DATOS -->
        <div class="mb-6 space-y-2">

            <p>
                <strong>Código:</strong>
                ${solicitud.codigo}
            </p>

            <p>
                <strong>Fecha:</strong>
                ${solicitud.fecha}
            </p>

            <p>
                <strong>Estado:</strong>
                ${solicitud.estado}
            </p>

        </div>

        <!-- TABLA -->
        <div class="overflow-x-auto">

            <table class="w-full table-auto text-lg sm:text-xl">

                <thead>

                    <tr class="border-b border-gray-600">

                        <th class="py-3 text-left">
                            #
                        </th>

                        <th class="py-3 text-left">
                            Descripción
                        </th>

                        <th class="py-3 text-left">
                            Cantidad
                        </th>

                    </tr>

                </thead>

                <tbody>

                    ${solicitud.detalles.map((d, i) => `

                            <tr class="border-b border-gray-700">

                                <td class="py-3">
                                    ${i + 1}
                                </td>

                                <td class="py-3">
                                    ${d.descripcion}
                                </td>

                                <td class="py-3">
                                    ${d.cantidad}
                                </td>

                            </tr>

                        `).join('')}

                </tbody>

            </table>

        </div>
    `;

            document.getElementById('detalleContenido').innerHTML = html;

            document.getElementById('modal').classList.remove('hidden');
            document.getElementById('modal').classList.add('flex');
        }

        function cerrarModal() {
            document.getElementById('modal').classList.add('hidden');
        }
    </script>

@endsection

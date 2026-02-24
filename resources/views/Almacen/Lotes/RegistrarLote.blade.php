@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">

                <div class="card shadow">
                    <div class="card-header text-white text-center" style="background-color:#134e4a;">
                        <strong>FORMULARIO DE REGISTRO DE LOTES</strong>
                    </div>

                    <div class="card-body">

                        <form method="POST" action="{{ route('Lote.store') }}">
                            @csrf

                            {{-- BUSCADOR DE PRODUCTOS --}}
                            <div class="form-group mb-3 position-relative overflow-visible">
                                <label>Producto</label>
                                <input type="text" id="buscarProducto" class="form-control"
                                    placeholder="Buscar por nombre, código o marca" autocomplete="off">

                                <input type="hidden" name="producto_id" id="producto_id">

                                {{-- RESULTADOS --}}
                                <div id="resultadoProductos" class="list-group position-absolute w-100"
                                    style="z-index:1000; display:none;">
                                </div>
                            </div>

                            {{-- INFO PRODUCTO --}}
                            <div id="infoProducto" class="alert alert-info d-none">
                                <strong>Producto:</strong>
                                <span id="productoNombre"></span><br>

                                <strong>Saldo actual:</strong>
                                <span id="productoSaldo"></span>
                            </div>

                            {{-- CAMPOS ADICIONALES EN MISMA LÍNEA --}}
                            <div class="row mb-3">

                                <div class="col-12 col-md-6">
                                    <label>Factura</label>
                                    <input type="text" name="factura" class="form-control">
                                </div>

                                <div class="col-12 col-md-6">
                                    <label>Recibo</label>
                                    <input type="text" name="Recibo" class="form-control">
                                </div>

                            </div>

                            <div class="row">

                                <div class="col-12 col-md-4 mb-3">
                                    <label>Código de Lote</label>
                                    <input type="text" name="codigo" class="form-control" required>
                                </div>

                                <div class="col-12 col-md-4 mb-3">
                                    <label>Cantidad</label>
                                    <input type="number" step="0.01" name="cantidad" class="form-control" required>
                                </div>

                                <div class="col-12 col-md-4 mb-3">
                                    <label>Precio Unitario</label>
                                    <input type="number" step="0.01" name="precio" class="form-control" required>
                                </div>

                                <div class="col-12 col-md-6 mb-3">
                                    <label>Fecha Vencimiento</label>
                                    <input type="date" name="fec_venc" class="form-control">
                                </div>

                                <div class="col-12 col-md-6 mb-3">
                                    <label>Origen</label>
                                    <select name="origen_id" class="form-control" required>
                                        <option value="">Seleccione</option>
                                        @foreach ($origenes as $origen)
                                            <option value="{{ $origen->id }}">
                                                {{ $origen->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- BOTÓN --}}
                                <div class="col-12 mt-3">
                                    <button type="submit" class="btn btn-lg w-100 text-white"
                                        style="background-color:#134e4a;">
                                        GUARDAR LOTE
                                    </button>
                                </div>

                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('js')
    {{-- SWEETALERT CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        let timeout = null;

        document.getElementById('buscarProducto').addEventListener('keyup', function() {

            clearTimeout(timeout);
            const query = this.value;
            const resultados = document.getElementById('resultadoProductos');

            if (query.length < 2) {
                resultados.style.display = 'none';
                return;
            }

            timeout = setTimeout(() => {
                fetch(`/Almacen/buscar-producto?q=${query}`)
                    .then(res => res.json())
                    .then(data => {

                        resultados.innerHTML = '';

                        if (!data || data.length === 0) {
                            resultados.style.display = 'none';
                            return;
                        }

                        data.forEach(item => {
                            const option = document.createElement('a');
                            option.classList.add('list-group-item', 'list-group-item-action');
                            option.innerText = item.nombre;

                            option.onclick = () => {
                                document.getElementById('buscarProducto').value = item
                                    .nombre;
                                document.getElementById('producto_id').value = item.id;

                                document.getElementById('productoNombre').innerText = item
                                    .nombre;
                                document.getElementById('productoSaldo').innerText = item
                                    .saldo;

                                document.getElementById('infoProducto').classList.remove(
                                    'd-none');
                                resultados.style.display = 'none';
                            };

                            resultados.appendChild(option);
                        });

                        resultados.style.display = 'block';
                    });
            }, 300);
        });


        // VALIDACIÓN ANTES DE ENVIAR
        document.querySelector('form').addEventListener('submit', function(e) {

            const productoId = document.getElementById('producto_id').value;

            if (!productoId) {
                e.preventDefault();

                Swal.fire({
                    icon: 'warning',
                    title: 'Producto requerido',
                    text: 'Debe seleccionar un producto de la lista.',
                    confirmButtonColor: '#134e4a'
                });
            }
        });
    </script>

    {{-- ALERTAS SWEETALERT --}}
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: "{{ session('success') }}",
                confirmButtonColor: '#134e4a'
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: "{{ session('error') }}",
                confirmButtonColor: '#134e4a'
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'warning',
                title: 'Errores de validación',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                confirmButtonColor: '#134e4a'
            });
        </script>
    @endif
@endsection


@push('styles')
    <style>
        /* permitir que el dropdown salga */
        .overflow-visible {
            overflow: visible !important;
        }

        /* ===== AUTOCOMPLETE ===== */
        #resultadoProductos {
            background-color: #1f2937 !important;
            border: 1px solid #374151 !important;
        }

        #resultadoProductos .list-group-item {
            background-color: #1f2937 !important;
            color: #e5e7eb !important;
            border: none !important;
        }

        #resultadoProductos .list-group-item-action:hover {
            background-color: #374151 !important;
            color: #ffffff !important;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {

            .card {
                margin: 10px;
            }

            .card-header strong {
                font-size: 14px;
            }

            #resultadoProductos {
                max-height: 250px;
                overflow-y: auto;
            }

            .btn-lg {
                font-size: 16px;
                padding: 12px;
            }

            .form-control {
                font-size: 14px;
            }
        }
    </style>
@endpush

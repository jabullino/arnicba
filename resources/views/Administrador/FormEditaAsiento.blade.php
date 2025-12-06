@extends('layouts.app')
@section('content')
    <style>
        /* ------------------ General ------------------ */
        .card {
            max-width: 540px;
            margin: auto;
        }

        .form-control {
            width: 100%;
            padding: 6px 8px;
            box-sizing: border-box;
        }

        button {
            cursor: pointer;
        }

        /* ------------------ Responsive ------------------ */
        @media (max-width: 1024px) {
            .card {
                width: 90%;
            }

            .card-header {
                font-size: 1.2rem;
            }

            label {
                font-size: 0.95rem;
            }

            .form-control {
                padding: 5px;
            }

            button {
                width: 100%;
            }
        }

        @media (max-width: 768px) {
            .card {
                width: 95%;
            }

            .card-header {
                font-size: 1rem;
            }

            label {
                font-size: 0.9rem;
            }

            .form-control {
                padding: 4px;
            }

            button {
                font-size: 0.9rem;
            }
        }

        @media (max-width: 480px) {
            .card {
                width: 100%;
            }

            .card-header {
                font-size: 0.85rem;
            }

            label {
                font-size: 0.8rem;
            }

            .form-control {
                padding: 3px;
                font-size: 0.85rem;
            }

            button {
                font-size: 0.85rem;
            }
        }
    </style>

    <div><!-- div principal -->
        <div class='card'>
            @if (session('AsientoEditado'))
                <div class="alert alert-success text-center">
                    {{ session('AsientoEditado') }}
                </div>
            @endif
            <div class='card-header text-center bold bg-sky-900 text-white'>
                <h1>FORMULARIO PARA LA EDICIÓN DE ASIENTOS DE DIARIO</h1>
            </div>

            <form action="{{ route('Asientos.update', $asiento->id) }}" method='post'>
                @csrf
                @method('PATCH')
                <div class="card-body grid grid-cols-2 gap-4">

                    <!-- Campos del formulario -->
                    <div class='mb-2 form-group'>
                        <label for="numinterno">Num. Interno</label>
                        <input type="text" name='numinterno' readonly value='{{ $asiento->id }}'
                            class='form-control text-center'>
                        @if ($errors->has('numinterno'))
                            <div style="color: red;">{{ $errors->first('numinterno') }}</div>
                        @endif
                    </div>

                    <div class='mb-2 form-group'>
                        <label for="fecha">Fecha</label>
                        <input type="date" name='fecha' value='{{ $asiento->fec_asiento }}' class='form-control'>
                        @if ($errors->has('fecha'))
                            <div style="color: red;">{{ $errors->first('fecha') }}</div>
                        @endif
                    </div>

                    <div class='mb-2 form-group'>
                        <label for="tc">Tipo Cambio Compra</label>
                        <input type="text" name='tc' id='tc'
                            value="{{ $tipocambiocompra->getTipocambiocompra($asiento->tc_id) }}"
                            class='form-control text-center'>
                        @if ($errors->has('tc'))
                            <div style="color: red;">{{ $errors->first('tc') }}</div>
                        @endif
                    </div>

                    <div class='mb-2 form-group'>
                        <label for="tv">Tipo Cambio Venta</label>
                        <input type="text" name='tv' id='tv'
                            value="{{ $tipocambioventa->getTipocambioventa($asiento->tv_id) }}"
                            class='form-control text-center'>
                        @if ($errors->has('tv'))
                            <div style="color: red;">{{ $errors->first('tv') }}</div>
                        @endif
                    </div>

                    <!-- Factura / Recibo -->
                    <div class='mb-2 form-group'>
                        <label for="factura">Factura</label>
                        <input type="text" name='factura' id='factura' value="{{ $asiento->factura }}"
                            class='form-control text-center'>
                        @if ($errors->has('factura'))
                            <div style="color: red;">{{ $errors->first('factura') }}</div>
                        @endif
                    </div>

                    <div class='mb-2 form-group'>
                        <label for="recibo">Recibo</label>
                        <input type="text" name='recibo' id='recibo' value="{{ $asiento->recibo }}"
                            class='form-control text-center'>
                        @if ($errors->has('recibo'))
                            <div style="color: red;">{{ $errors->first('recibo') }}</div>
                        @endif
                    </div>

                    <!-- Botón habilitar factura/recibo ocupando dos columnas -->
                    <div class="mb-2 form-group col-span-2">
                        <button class='bg-gray-700 h-16 text-white bold rounded w-full'>Habilitar Factura / Recibo</button>
                    </div>

                    <!-- Resto de campos de selección y montos -->
                    <div class="mb-2 form-group">
                        <label for="cuenta">Cuenta</label>
                        <select name="cuenta" id="cuenta" class="form-control">
                            <option value="{{ $asiento->cuenta }}">{{ $cuenta->getCuenta($asiento->cuenta) }}</option>
                            @foreach ($cuentas as $cue)
                                <option value="{{ $cue->id }}">{{ $cue->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-2 form-group">
                        <label for="subcuenta">Subcuenta</label>
                        <select id="subcuenta" name="subcuenta" class="form-control">
                            <option value="{{ $asiento->sub_cuenta }}">{{ $subcuenta->getSubcuenta($asiento->sub_cuenta) }}</option>
                        </select>
                    </div>

                    <div class="mb-2 form-group">
                        <label for="importebs">Importe Bs.</label>
                        <input type="text" name="importebs" id="importebs" value="{{ $asiento->monto_bs }}"
                            class="form-control text-right">
                    </div>

                    <div class="mb-2 form-group">
                        <label for="importesus">Importe $us.</label>
                        <input type="text" name="importesus" id="importesus" value="{{ $asiento->monto_sus }}"
                            class="form-control text-right">
                    </div>

                    <div class="mb-2 form-group">
                        <label for="origenfondos">Origen de Fondos</label>
                        <select name="origenfondos" id="origenfondos" class="form-control">
                            <option value="{{ $asiento->origenfondos_id }}">
                                {{ $origenfondos->getOrigenfondos($asiento->origenfondos_id) }}</option>
                            @foreach ($origenesfondos as $origen)
                                <option value="{{ $origen->id }}">{{ $origen->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-2 form-group">
                        <label for="tipomovimiento">Tipo Movimiento</label>
                        <select name="tipomovimiento" id="tipomovimiento" class="form-control">
                            <option value="{{ $asiento->tipomovimiento_id }}">
                                {{ $tipomovimiento->getTipomovimento($asiento->tipomovimiento_id) }}</option>
                            @foreach ($tiposmovimientos as $tipo)
                                <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Botón Registrar ocupando dos columnas -->
                    <div class="mb-2 form-group col-span-2">
                        <button type="submit" class="btn btn-primary bg-sky-900 w-full">Registrar</button>
                    </div>

                </div><!-- fin card-body -->
            </form>
        </div><!-- fin card -->
    </div><!-- fin div principal -->

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Validaciones existentes
            function soloEnteros(input) {
                input.addEventListener('input', function() {
                    this.value = this.value.replace(/\D/g, '');
                });
            }

            function soloDecimales(input) {
                input.addEventListener('input', function() {
                    this.value = this.value.replace(/[^0-9.]/g, '');
                    const partes = this.value.split('.');
                    if (partes.length > 2) {
                        this.value = partes[0] + '.' + partes[1];
                    }
                });
            }

            soloEnteros(document.querySelector('input[name="factura"]'));
            soloEnteros(document.querySelector('input[name="recibo"]'));
            soloDecimales(document.querySelector('input[name="importebs"]'));
            soloDecimales(document.querySelector('input[name="importesus"]'));

            document.body.addEventListener('input', function(e) {
                if (e.target.matches('input[name="factura"]') || e.target.matches('input[name="recibo"]')) {
                    soloEnteros(e.target);
                }
                if (e.target.matches('input[name="importebs"]') || e.target.matches('input[name="importesus"]')) {
                    soloDecimales(e.target);
                }
            });

            // ------------------- SweetAlert -------------------
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: "{{ session('success') }}",
                    confirmButtonText: 'Aceptar'
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: '¡Error!',
                    text: "{{ session('error') }}",
                    confirmButtonText: 'Aceptar'
                });
            @endif
        });
    </script>
    <script>
        document.getElementById('cuenta').addEventListener('change', function() {
            const cuentaId = this.value;
            const subcuentaSelect = document.getElementById('subcuenta');
            document.getElementById('subcuenta').disabled = false;
            // Limpiar las opciones de subcuentas
            subcuentaSelect.innerHTML = '<option value="">Seleccione una subcuenta</option>';

            if (cuentaId) {
                // Hacer una solicitud fetch para obtener las subcuentas
                fetch(`/subcuentas/${cuentaId}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(subcuenta => {
                            const option = document.createElement('option');
                            option.value = subcuenta.id;
                            option.textContent = subcuenta.nombre;
                            subcuentaSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error al cargar las subcuentas:', error));
            }
        });
    </script>
    <script>
        document.getElementById('importebs').addEventListener('blur', function() {

            let importebs = this.value.trim(); // Elimina espacios
            importebs = this.value.replace(',', '.');
            // Reemplaza coma por punto (si es necesario)
            let tc = document.getElementById('tc').value.trim(); // Elimina espacios
            tc = tc.replace(',', '.');


            document.getElementById('importesus').value = parseFloat(importebs / tc).toFixed(2);

        })
    </script>
    <script>
        document.getElementById('importesus').addEventListener('blur', function() {

            let importesus = this.value.trim();
            importesus = this.value.replace(',', '.');
            let tc = document.getElementById('tc').value.trim();
            tc = tc.replace(',', '.');
            document.getElementById('importebs').value = parseFloat(importesus * tc).toFixed(2);

        })
    </script>

<script>
    document.getElementById('tc').addEventListener('blur', async function() {
        
        
        let nuevotcvalor= this.value.trim();
            nuevotcvalor = this.value.replace(',', '.');
        
        try {
            const response = await fetch('/check-tc', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}', // Laravel CSRF token
                },
                body: JSON.stringify({ valor: nuevotcvalor }),
            });

            const data = await response.json();

            if (data.exists) {
                this.value = data.valor;
                console.log('El valor ya existe en la base de datos.');
            } else {
                // Insertamos el valor y lo devolvemos
                
                this.value = data.valor; // Establecer el valor en el input
                console.log('Valor insertado correctamente.');
            }

        } catch (error) {
            console.error('Error al hacer la solicitud:', error);
        }
    });
</script>

<script>
    factura.addEventListener('focus', function() {
            // Deshabilitamos el input recibo
            recibo.disabled = true;
    });
</script>

<script>
    recibo.addEventListener('focus', function() {
            // Deshabilitamos el input recibo
            factura.disabled = true;
    });
</script>
@endsection

@endsection

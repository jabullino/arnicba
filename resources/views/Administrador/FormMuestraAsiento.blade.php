@extends('layouts.app')
@section('content')

<style>
    /* General responsive */
    @media (max-width: 1024px) {
        .card {
            width: 90% !important;
            margin: auto !important;
        }
        .form-control {
            width: 100% !important;
        }
        button {
            width: 100% !important;
        }
    }

    @media (max-width: 640px) {
        .card {
            width: 95% !important;
        }
        label {
            font-size: 0.875rem !important;
        }
        input.form-control,
        select.form-control {
            font-size: 0.875rem !important;
        }
        button {
            font-size: 0.875rem !important;
        }
    }
</style>

<div><!--- div principal --->

    <div class='card w-[540px] mx-auto'><!---div card --->
        @if (session('AsientoEditado'))
        <div class="alert alert-success text-center">
            {{ session('AsientoEditado') }}
        </div>
        @endif
        <div class='card-header text-center bold bg-sky-900 text-white'>
            <h1>FORMULARIO VISTA DE ASIENTOS DE DIARIO</h1>
        </div>
        <form action="" method='post'>
            @csrf

            <div class="card-body sm:grid grid-cols-1 md:grid grid-cols-2 gap-2 ">

                <div class='mb-2 form-group'>
                    <label for="numinterno">Num. Interno</label>
                    <input type="text" name='numinterno' readonly value='{{ $asiento->id }}'
                        class='form-control text-center'>
                    @if ($errors->has('numinterno'))
                    <div style="color: red;">
                        {{ $errors->first('numinterno') }}
                    </div>
                    @endif
                </div>

                <div class='mb-2 form-group'>
                    <label for="fecha">Fecha</label>
                    <input type="date" name='fecha' readonly value='{{ $asiento->fec_asiento }}' class='form-control'>
                    @if ($errors->has('fecha'))
                    <div style="color: red;">
                        {{ $errors->first('fecha') }}
                    </div>
                    @endif
                </div>

                <div class='mb-2 form-group'>
                    <label for="tc">Tipo Cambio Compra</label>
                    <input type="text" name='tc' id='tc' value={{ $tipocambiocompra->getTipocambiocompra($asiento->tc_id) }} readonly
                        class='form-control text-center'>
                    @if ($errors->has('tc'))
                    <div style="color: red;">
                        {{ $errors->first('tc') }}
                    </div>
                    @endif
                </div>

                <div class='mb-2 form-group'>
                    <label for="tv">Tipo Cambio Venta</label>
                    <input type="text" name='tv' id='tv' readonly value={{ $tipocambioventa->getTipocambioventa($asiento->tv_id) }}
                        class='form-control text-center'>
                    @if ($errors->has('tv'))
                    <div style="color: red;">
                        {{ $errors->first('tv') }}
                    </div>
                    @endif
                </div>

                <div class='mb-2 form-group'>
                    <label for="factura">Factura</label>
                    <input type="text" name='factura' readonly id='factura' value="{{ $asiento->factura }}"
                        class='form-control'>
                    @if ($errors->has('factura'))
                    <div style="color: red;">
                        {{ $errors->first('factura') }}
                    </div>
                    @endif
                </div>

                <div class='mb-2 form-group'>
                    <label for="recibo">Recibo</label>
                    <input type="text" name='recibo' readonly id='recibo' value="{{ $asiento->recibo }}"
                        class='form-control'>
                    @if ($errors->has('recibo'))
                    <div style="color: red;">
                        {{ $errors->first('recibo') }}
                    </div>
                    @endif
                </div>

                <div class='mb-2 sm:grid grid-cols-1 md:grid grid-cols-2 w-full'>
                    <button class='bg-gray-700 h-16 text-white bold rounded w-[520px]'>Habilitar Factura / Recibo</button>
                </div>
                <br>

                <div class='mb-2 form-group'>
                    <label for="cuenta">Cuenta</label>
                    <select name="cuenta" id="cuenta" readonly class='form-control'>
                        <option value="{{$asiento->cuenta}}">{{$cuenta->getCuenta($asiento->cuenta)}} </option>
                        @foreach ($cuentas as $cue)
                        <option value="{{ $cue->id }}">{{ $cue->nombre }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('cuenta'))
                    <div style="color: red;">
                        {{ $errors->first('cuenta') }}
                    </div>
                    @endif
                </div>

                <div class='mb-2 form-group'>
                    <label for="subcuenta">Subcuenta</label>
                    <select id="subcuenta" readonly name="subcuenta" class='form-control' disabled>
                        <option value="{{$asiento->sub_cuenta}}">{{$subcuenta->getSubcuenta($asiento->sub_cuenta)}}</option>
                    </select>
                    @if ($errors->has('subcuenta'))
                    <div style="color: red;">
                        {{ $errors->first('subcuenta') }}
                    </div>
                    @endif
                </div>

                <div class='mb-2 form-group'>
                    <label for="importebs">Importe Bs.</label>
                    <input type="text" readonly name='importebs' id='importebs' value="{{$asiento->monto_bs}}"
                        class='form-control'>
                    @if ($errors->has('importebs'))
                    <div style="color: red;">
                        {{ $errors->first('importebs') }}
                    </div>
                    @endif
                </div>

                <div class='mb-2 form-group'>
                    <label for="importesus">Importe $us.</label>
                    <input type="text" readonly name='importesus' id='importesus' value="{{ $asiento->monto_sus }}"
                        class='form-control'>
                    @if ($errors->has('importsus'))
                    <div style="color: red;">
                        {{ $errors->first('importesus') }}
                    </div>
                    @endif
                </div>

                <div class='mb-2 form-group'>
                    <label for="origenfondos">Origen de Fondos</label>
                    <select name="origenfondos" id="origenfondos" readonly class='form-control'>
                        <option value="{{$asiento->origenfondos_id}}">{{$origenfondos->getOrigenfondos($asiento->origenfondos_id)}}</option>
                        @foreach ($origenesfondos as $origen)
                        <option value="{{ $origen->id }}">{{ $origen->nombre }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('origenfondos'))
                    <div style="color: red;">
                        {{ $errors->first('origenfondos') }}
                    </div>
                    @endif
                </div>

                <div class='mb-2 form-group'>
                    <label for="tipomovimiento">Tipo Movimiento</label>
                    <select name="tipomovimiento" id="tipomovimiento" readonly class='form-control'>
                        <option value="{{$asiento->tipomovimiento_id}}">{{$tipomovimiento->getTipomovimento($asiento->tipomovimiento_id)}}</option>
                        @foreach ($tiposmovimientos as $tipo)
                        <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('tipomovimento'))
                    <div style="color: red;">
                        {{ $errors->first('tipomovimento') }}
                    </div>
                    @endif
                </div>

                <div class='mb-2 sm:grid grid-cols-1 md:grid grid-cols-2 w-full'>
                    <button type='submit' class='btn btn-primary bg-sky-900 w-[520px]'>Registrar</button>
                </div>

            </div><!--fin div card-body --->
        </form>
    </div><!--- fin div card --->

</div><!--- fin div principal --->

@section('js')
<script>
    document.getElementById('cuenta').addEventListener('change', function() {
        const cuentaId = this.value;
        const subcuentaSelect = document.getElementById('subcuenta');
        document.getElementById('subcuenta').disabled = false;
        subcuentaSelect.innerHTML = '<option value="">Seleccione una subcuenta</option>';
        if (cuentaId) {
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

    document.getElementById('importebs').addEventListener('blur', function() {
        let importebs = this.value.trim().replace(',', '.');
        let tc = document.getElementById('tc').value.trim().replace(',', '.');
        document.getElementById('importesus').value = parseFloat(importebs / tc).toFixed(2);
    });

    document.getElementById('importesus').addEventListener('blur', function() {
        let importesus = this.value.trim().replace(',', '.');
        let tc = document.getElementById('tc').value.trim().replace(',', '.');
        document.getElementById('importebs').value = parseFloat(importesus * tc).toFixed(2);
    });

    document.getElementById('tc').addEventListener('blur', async function() {
        let nuevotcvalor = this.value.trim().replace(',', '.');
        try {
            const response = await fetch('/check-tc', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ valor: nuevotcvalor }),
            });
            const data = await response.json();
            this.value = data.valor;
        } catch (error) {
            console.error('Error al hacer la solicitud:', error);
        }
    });

    factura.addEventListener('focus', function() { recibo.disabled = true; });
    recibo.addEventListener('focus', function() { factura.disabled = true; });
</script>
@endsection

@endsection

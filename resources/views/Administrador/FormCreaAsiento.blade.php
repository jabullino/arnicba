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
        .card-header h1 {
            font-size: 1.2rem;
        }
        .form-group label {
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
        .card-header h1 {
            font-size: 1rem;
        }
        .form-group label {
            font-size: 0.9rem;
        }
        .form-control {
            padding: 4px;
        }
        button {
            width: 100%;
            font-size: 0.9rem;
        }
    }

    @media (max-width: 480px) {
        .card {
            width: 100%;
        }
        .card-header h1 {
            font-size: 0.85rem;
        }
        .form-group label {
            font-size: 0.8rem;
        }
        .form-control {
            padding: 3px;
            font-size: 0.85rem;
        }
        button {
            width: 100%;
            font-size: 0.85rem;
        }
    }
</style>

<div><!--- div principal --->
    <div class='card'><!--- div card --->
        @if (session('AsientoCreado'))
            <div class="alert alert-success text-center">
                {{ session('AsientoCreado') }}
            </div>
        @endif

        <div class='card-header text-center bold bg-sky-900 text-white'>
            <h1>FORMULARIO PARA LA CREACIÓN DE ASIENTOS DE DIARIO</h1>
        </div>

        <form action="{{ route('Asientos.store') }}" method='post'>
            @csrf
            <div class="card-body sm:grid grid-cols-1 md:grid grid-cols-2 gap-2">

                <div class='mb-2 form-group'>
                    <label for="numinterno">Num. Interno</label>
                    <input type="text" name='numinterno' readonly value='{{ $numinterno }}' class='form-control text-center'>
                    @if ($errors->has('numinterno'))
                        <div style="color: red;">{{ $errors->first('numinterno') }}</div>
                    @endif
                </div>

                <div class='mb-2 form-group'>
                    <label for="fecha">Fecha</label>
                    <input type="date" name='fecha' value='{{ old('fecha') }}' class='form-control'>
                    @if ($errors->has('fecha'))
                        <div style="color: red;">{{ $errors->first('fecha') }}</div>
                    @endif
                </div>

                <div class='mb-2 form-group'>
                    <label for="tc">Tipo Cambio Compra</label>
                    <input type="text" name='tc' id='tc' value={{ $tc }} class='form-control text-center'>
                    @if ($errors->has('tc'))
                        <div style="color: red;">{{ $errors->first('tc') }}</div>
                    @endif
                </div>

                <div class='mb-2 form-group'>
                    <label for="tv">Tipo Cambio Venta</label>
                    <input type="text" name='tv' id='tv' value={{ $tv }} class='form-control text-center'>
                    @if ($errors->has('tv'))
                        <div style="color: red;">{{ $errors->first('tv') }}</div>
                    @endif
                </div>

                <div class='mb-2 form-group'>
                    <label for="factura">Factura</label>
                    <input type="text" name='factura' id='factura' value="{{ old('factura') }}" class='form-control'>
                    @if ($errors->has('factura'))
                        <div style="color: red;">{{ $errors->first('factura') }}</div>
                    @endif
                </div>

                <div class='mb-2 form-group'>
                    <label for="recibo">Recibo</label>
                    <input type="text" name='recibo' id='recibo' value="{{ old('recibo') }}" class='form-control'>
                    @if ($errors->has('recibo'))
                        <div style="color: red;">{{ $errors->first('recibo') }}</div>
                    @endif
                </div>

                <div class='mb-2 w-full block col-span-2'>
                    <button class='bg-gray-700 h-16 text-white bold rounded w-full'>Habilitar Factura / Recibo</button>
                </div>

                <div class='mb-2 form-group'>
                    <label for="cuenta">Cuenta</label>
                    <select name="cuenta" id="cuenta" class='form-control'>
                        <option value="">Seleccionar una cuenta</option>
                        @foreach ($cuentas as $cue)
                            <option value="{{ $cue->id }}">{{ $cue->nombre }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('cuenta'))
                        <div style="color: red;">{{ $errors->first('cuenta') }}</div>
                    @endif
                </div>

                <div class='mb-2 form-group'>
                    <label for="subcuenta">Subcuenta</label>
                    <select id="subcuenta" name="subcuenta" class='form-control' disabled>
                        <option value="">Seleccionar subcuenta</option>
                    </select>
                    @if ($errors->has('subcuenta'))
                        <div style="color: red;">{{ $errors->first('subcuenta') }}</div>
                    @endif
                </div>

                <div class='mb-2 form-group'>
                    <label for="importebs">Importe Bs.</label>
                    <input type="text" name='importebs' id='importebs' value="{{ old('importebs') }}" class='form-control'>
                    @if ($errors->has('importebs'))
                        <div style="color: red;">{{ $errors->first('importebs') }}</div>
                    @endif
                </div>

                <div class='mb-2 form-group'>
                    <label for="importesus">Importe $us.</label>
                    <input type="text" name='importesus' id='importesus' value="{{ old('importesus') }}" class='form-control'>
                    @if ($errors->has('importesus'))
                        <div style="color: red;">{{ $errors->first('importesus') }}</div>
                    @endif
                </div>

                <div class='mb-2 form-group'>
                    <label for="origenfondos">Origen de Fondos</label>
                    <select name="origenfondos" id="origenfondos" class='form-control'>
                        <option value="1">Fondos Propios</option>
                        @foreach ($origenfondos as $origen)
                            <option value="{{ $origen->id }}">{{ $origen->nombre }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('origenfondos'))
                        <div style="color: red;">{{ $errors->first('origenfondos') }}</div>
                    @endif
                </div>

                <div class='mb-2 form-group'>
                    <label for="tipomovimiento">Tipo Movimiento</label>
                    <select name="tipomovimiento" id="tipomovimiento" class='form-control'>
                        <option value="1">Egreso Bs.</option>
                        @foreach ($tipomovimiento as $tipo)
                            <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('tipomovimiento'))
                        <div style="color: red;">{{ $errors->first('tipomovimiento') }}</div>
                    @endif
                </div>

                <div class='mb-2 w-full col-span-2'>
                    <button type='submit' class='btn btn-primary bg-sky-900 w-full'>Registrar</button>
                </div>

            </div><!--fin div card-body --->
        </form>
    </div><!--- fin div card --->
</div><!--- fin div principal --->
@endsection

@section('js')
<script>
    // Subcuentas dinámicas
    document.getElementById('cuenta').addEventListener('change', function() {
        const cuentaId = this.value;
        const subcuentaSelect = document.getElementById('subcuenta');
        subcuentaSelect.disabled = false;
        subcuentaSelect.innerHTML = '<option value="">Seleccione una subcuenta</option>';
        if(cuentaId){
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
            .catch(err => console.error('Error al cargar subcuentas:', err));
        }
    });

    // Conversión Bs a $us
    document.getElementById('importebs').addEventListener('blur', function() {
        let importebs = parseFloat(this.value.replace(',', '.')) || 0;
        let tc = parseFloat(document.getElementById('tc').value.replace(',', '.')) || 1;
        document.getElementById('importesus').value = (importebs / tc).toFixed(2);
    });

    // Conversión $us a Bs
    document.getElementById('importesus').addEventListener('blur', function() {
        let importesus = parseFloat(this.value.replace(',', '.')) || 0;
        let tc = parseFloat(document.getElementById('tc').value.replace(',', '.')) || 1;
        document.getElementById('importebs').value = (importesus * tc).toFixed(2);
    });

    // Validación TC
    document.getElementById('tc').addEventListener('blur', async function() {
        let valor = parseFloat(this.value.replace(',', '.')) || 0;
        try {
            const response = await fetch('/check-tc', {
                method: 'POST',
                headers: {'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
                body: JSON.stringify({ valor })
            });
            const data = await response.json();
            this.value = data.valor;
        } catch(e) { console.error(e); }
    });

    // Habilitar/Deshabilitar factura/recibo
    document.getElementById('factura').addEventListener('focus', ()=>{ document.getElementById('recibo').disabled=true; });
    document.getElementById('recibo').addEventListener('focus', ()=>{ document.getElementById('factura').disabled=true; });
</script>
@endsection

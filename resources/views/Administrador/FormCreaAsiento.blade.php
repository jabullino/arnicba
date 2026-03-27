@extends('layouts.app')
@section('content')

<style>
    .card { max-width: 540px; margin: auto; }
    .form-control { width: 100%; padding: 6px 8px; box-sizing: border-box; }
    button { cursor: pointer; }

    @media (max-width: 1024px) {
        .card { width: 90%; }
        .card-header h1 { font-size: 1.2rem; }
        .form-group label { font-size: 0.95rem; }
        .form-control { padding: 5px; }
        button { width: 100%; }
    }

    @media (max-width: 768px) {
        .card { width: 95%; }
        .card-header h1 { font-size: 1rem; }
        .form-group label { font-size: 0.9rem; }
        .form-control { padding: 4px; }
        button { width: 100%; font-size: 0.9rem; }
    }

    @media (max-width: 480px) {
        .card { width: 100%; }
        .card-header h1 { font-size: 0.85rem; }
        .form-group label { font-size: 0.8rem; }
        .form-control { padding: 3px; font-size: 0.85rem; }
        button { width: 100%; font-size: 0.85rem; }
    }
</style>

<div>
    <div class='card'>
        @if (session('AsientoCreado'))
            <div class="alert alert-success text-center">
                {{ session('AsientoCreado') }}
            </div>
        @endif

```
    <div class='card-header text-center bold bg-sky-900 text-white'>
        <h1>FORMULARIO PARA LA CREACIÓN DE ASIENTOS DE DIARIO</h1>
    </div>

    <form action="{{ route('Asientos.store') }}" method='post'>
        @csrf
        <div class="card-body sm:grid grid-cols-1 md:grid grid-cols-2 gap-2">

            <div class='mb-2 form-group'>
                <label>Num. Interno</label>
                <input type="text" name='numinterno' readonly value='{{ $numinterno }}' class='form-control text-center'>
            </div>

            <div class='mb-2 form-group'>
                <label>Fecha</label>
                <input type="date" name='fecha' value='{{ old('fecha') }}' class='form-control'>
            </div>

            <div class='mb-2 form-group'>
                <label>Tipo Cambio Compra</label>
                <input type="text" name='tc' id='tc' value={{ $tc }} class='form-control text-center'>
            </div>

            <div class='mb-2 form-group'>
                <label>Tipo Cambio Venta</label>
                <input type="text" name='tv' id='tv' value={{ $tv }} class='form-control text-center'>
            </div>

            <div class='mb-2 form-group'>
                <label>Factura</label>
                <input type="text" name='factura' id='factura' value="{{ old('factura') }}" class='form-control'>
            </div>

            <div class='mb-2 form-group'>
                <label>Recibo</label>
                <input type="text" name='recibo' id='recibo' value="{{ old('recibo') }}" class='form-control'>
            </div>

            <div class='mb-2 w-full block col-span-2'>
                <button class='bg-gray-700 h-16 text-white bold rounded w-full'>Habilitar Factura / Recibo</button>
            </div>

            <div class='mb-2 form-group'>
                <label>Cuenta</label>
                <select name="cuenta" id="cuenta" class='form-control'>
                    <option value="">Seleccionar una cuenta</option>
                    @foreach ($cuentas as $cue)
                        <option value="{{ $cue->id }}">{{ $cue->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class='mb-2 form-group'>
                <label>Subcuenta</label>
                <select id="subcuenta" name="subcuenta" class='form-control' disabled>
                    <option value="">Seleccionar subcuenta</option>
                </select>
            </div>

            <div class='mb-2 form-group'>
                <label>Importe Bs.</label>
                <input type="text" name='importebs' id='importebs' value="{{ old('importebs') }}" class='form-control'>
            </div>

            <div class='mb-2 form-group'>
                <label>Importe $us.</label>
                <input type="text" name='importesus' id='importesus' value="{{ old('importesus') }}" class='form-control'>
            </div>

            <div class='mb-2 form-group'>
                <label>Origen de Fondos</label>
                <select name="origenfondos" class='form-control'>
                    <option value="1">Fondos Propios</option>
                    @foreach ($origenfondos as $origen)
                        <option value="{{ $origen->id }}">{{ $origen->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class='mb-2 form-group'>
                <label>Tipo Movimiento</label>
                <select name="tipomovimiento" class='form-control'>
                    <option value="1">Egreso Bs.</option>
                    @foreach ($tipomovimiento as $tipo)
                        <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class='mb-2 w-full col-span-2'>
                <button type='submit' class='btn btn-primary bg-sky-900 w-full'>Registrar</button>
            </div>

        </div>
    </form>
</div>
```

</div>
@endsection

@section('js')

<script>
    // 🔹 Subcuentas dinámicas
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
            });
        }
    });

    // 🔹 Conversión Bs → $us
    document.getElementById('importebs').addEventListener('blur', function() {
        let importebs = parseFloat(this.value.replace(',', '.')) || 0;
        let tc = parseFloat(document.getElementById('tc').value.replace(',', '.')) || 1;
        document.getElementById('importesus').value = (importebs / tc).toFixed(2);
    });

    // 🔹 Conversión $us → Bs
    document.getElementById('importesus').addEventListener('blur', function() {
        let importesus = parseFloat(this.value.replace(',', '.')) || 0;
        let tc = parseFloat(document.getElementById('tc').value.replace(',', '.')) || 1;
        document.getElementById('importebs').value = (importesus * tc).toFixed(2);
    });

    // 🔹 Solo números
    function soloNumeros(e) {
        if (!/[0-9]/.test(e.key) && !["Backspace","Tab"].includes(e.key)) {
            e.preventDefault();
        }
    }

    document.getElementById('factura').addEventListener('keydown', soloNumeros);
    document.getElementById('recibo').addEventListener('keydown', soloNumeros);

    // 🔹 Números con decimal
    function numerosDecimal(e) {
        const input = e.target;
        if (["Backspace","Tab","ArrowLeft","ArrowRight","Delete"].includes(e.key)) return;
        if (/[0-9]/.test(e.key)) return;
        if (e.key === "." && !input.value.includes(".")) return;
        e.preventDefault();
    }

    document.getElementById('importebs').addEventListener('keydown', numerosDecimal);
    document.getElementById('importesus').addEventListener('keydown', numerosDecimal);

    // 🔹 Limpieza al pegar
    ['factura','recibo'].forEach(id => {
        document.getElementById(id).addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '');
        });
    });

    ['importebs','importesus'].forEach(id => {
        document.getElementById(id).addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9.]/g, '');
            const partes = this.value.split('.');
            if (partes.length > 2) {
                this.value = partes[0] + '.' + partes.slice(1).join('');
            }
        });
    });

    // 🔹 Habilitar factura/recibo
    document.getElementById('factura').addEventListener('focus', ()=>{ document.getElementById('recibo').disabled=true; });
    document.getElementById('recibo').addEventListener('focus', ()=>{ document.getElementById('factura').disabled=true; });

</script>

@endsection

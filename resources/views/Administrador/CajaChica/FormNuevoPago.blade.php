@extends('layouts.app')

@section('page_header')
@stop

@section('page_content')

<form action="{{ route('gastoscajachica.store') }}" method='POST'>
    @csrf

    <div class='card w-[450px] mx-auto'>
        <div class="card-header text-center font-bold">
            <h5>FORMULARIO DE PAGOS</h5>
        </div>

        <div id="saldo-container" class="form-group mb-2" style='width:130px;'>
            <label>Saldo Disponible</label>
            <span id="saldo" class="form-control text-right font-bold">
                {{ number_format($disponible, 2, '.', '') }}
            </span>
        </div>

        <div class="card-body">

            <!-- FECHA E IMPORTE -->
            <div class='grid grid-cols-2 gap-2'>
                <div class='form-group'>
                    <label>Fecha</label>
                    <input type="date" name="fecha" class='form-control' value="{{ old('fecha') }}">
                </div>

                <div class="form-group">
                    <label>Importe</label>
                    <input type="text" name="importe" id="importe" value="{{ old('importe') }}" class="form-control">
                </div>
            </div>

            <!-- CUENTAS -->
            <div class='grid grid-cols-2 gap-2 mt-2'>
                <div class='form-group'>
                    <label>Cuenta</label>
                    <select name="cuenta" id="cuenta" class='form-control'>
                        <option value="">Seleccione</option>
                        @foreach ($cuentas as $cu)
                            <option value="{{ $cu->id }}">{{ $cu->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class='form-group'>
                    <label>Sub Cuenta</label>
                    <select name="subcuenta" id="subcuenta" class='form-control' disabled></select>
                </div>
            </div>

            <!-- FACTURA / RECIBO -->
            <div class='grid grid-cols-2 gap-2 mt-2'>
                <div class='form-group'>
                    <label>Factura</label>
                    <input type="text" name='factura' id='factura' value="{{ old('factura') }}" class='form-control'>
                </div>

                <div class='form-group'>
                    <label>Recibo</label>
                    <input type="text" name='recibo' id='recibo' value="{{ old('recibo') }}" class='form-control'>
                </div>
            </div>

            <!-- BOTONES -->
            <div class="flex justify-end gap-2 mt-4">

                <button type="button" id="habilitar"
                    style="background-color:#6c757d; color:white; padding:10px 20px; border-radius:6px;">
                    Habilitar Factura / Recibo
                </button>

                <button type="submit"
                    style="background-color:#0f1947; color:white; padding:10px 20px; border-radius:6px;">
                    Registrar Pago
                </button>

            </div>

        </div>
    </div>

</form>

@stop


@section('css')
<style>
body, .wrapper, .content-wrapper {
    background-color: #343a40 !important;
    color: #fff !important;
}

.card {
    background-color: #2f353a !important;
    border: 1px solid #495057 !important;
}

.form-control {
    background-color: #3b4045 !important;
    color: #fff !important;
}

.swal2-popup {
    background-color: #2f353a !important;
    color: #fff !important;
}
</style>
@stop


@section('js')

<!-- SWEET ALERT -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- MENSAJES -->
@if(session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Éxito',
    text: @json(session('success')),
    timer: 3000,
    showConfirmButton: false
});
</script>
@endif

@if(session('error'))
<script>
Swal.fire({
    icon: 'error',
    title: 'Error',
    text: @json(session('error'))
});
</script>
@endif

@if ($errors->any())
<script>
Swal.fire({
    icon: 'error',
    title: 'Errores',
    html: `{!! implode('<br>', $errors->all()) !!}`
});
</script>
@endif


<script>
// SUBCUENTAS
document.getElementById('cuenta').addEventListener('change', function() {
    let id = this.value;
    let sub = document.getElementById('subcuenta');
    sub.innerHTML = '';
    sub.disabled = false;

    fetch(`/subcuentas/${id}`)
        .then(r => r.json())
        .then(data => {
            data.forEach(s => {
                let opt = document.createElement('option');
                opt.value = s.id;
                opt.textContent = s.nombre;
                sub.appendChild(opt);
            });
        });
});

// HABILITAR CAMPOS
document.getElementById('habilitar').addEventListener('click', function(e){
    e.preventDefault();

    document.getElementById('factura').disabled = false;
    document.getElementById('recibo').disabled = false;

    Swal.fire({
        icon:'info',
        title:'Campos habilitados',
        timer:1500,
        showConfirmButton:false
    });
});

// 🔥 SALDO DINÁMICO (RESTAURADO)
const importeInput = document.getElementById('importe');
const saldoSpan = document.getElementById('saldo');
const saldoInicial = parseFloat(saldoSpan.textContent) || 0;

importeInput.addEventListener('input', function() {

    let rawValue = this.value.replace(/[^0-9.]/g, '');

    const parts = rawValue.split('.');
    if (parts.length > 2) {
        rawValue = parts[0] + '.' + parts.slice(1).join('');
    }

    let importe = parseFloat(rawValue) || 0;
    let saldoActual = saldoInicial - importe;

    saldoSpan.textContent = saldoActual >= 0
        ? saldoActual.toFixed(2)
        : '0.00';

    if (importe > saldoInicial) {
        Swal.fire({
            icon: 'error',
            title: 'Monto excedido',
            text: 'El importe supera el saldo disponible'
        });

        this.value = '';
        saldoSpan.textContent = saldoInicial.toFixed(2);
        return;
    }

    this.value = rawValue;
});

importeInput.addEventListener('blur', function() {
    let importe = parseFloat(this.value) || 0;
    this.value = importe.toFixed(2);
});

</script>

@stop

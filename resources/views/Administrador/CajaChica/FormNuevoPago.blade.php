@extends('layouts.app')

@section('page_header')
@stop

<form action="{{ route('gastoscajachica.store') }}" method='POST'>
    @csrf

@section('page_content')
    @if (session('success'))
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

    @if (session('error'))
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

    <div class='card w-[450px] mx-auto'>
        <div class="card-header text-center font-bold">
            <h5>FORMULARIO DE PAGOS</h5>
        </div>

        <div id="saldo-container" class="form-group  mb-2 " style='width:130px; margin-right:0px'>
            <label for="importe">Saldo Disponible</label>
            <span id="saldo" class="form-control text-right font-bold text-basic ">
                {{ number_format($disponible, 2, '.', '') }}
            </span>
        </div>

        <div class="card-body">
            <div id='fecha-importe' class='grid grid-cols-2'>
                <div class='mb-2 form-group'>
                    <label for="fecha">Fecha</label>
                    <input type="date" name="fecha" class='form-control' value="{{ old('fecha') }}">
                </div>
                <div class="mb-2 form-group">
                    <label for="importe">Importe</label>
                    <input type="text" name="importe" id="importe" value="{{ old('importe') }}"
                        class="form-control">
                    @if ($errors->has('importe'))
                        <div style="color: red;">
                            {{ $errors->first('importe') }}
                        </div>
                    @endif
                </div>
            </div>

            <div id='cuentas' class='grid grid-cols-2'>
                <div class='mb-2 form-group'>
                    <label for="cuenta">Cuenta</label>
                    <select name="cuenta" id="cuenta" class='form-control text-black'>
                        <option value="default" class='text-black'>Escoja una cuenta</option>
                        @foreach ($cuentas as $cu)
                            <option value="{{ $cu->id }}" class='text-black'>{{ $cu->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class='mb-2 form-group'>
                    <label for="subcuenta">Sub Cuenta</label>
                    <select name="subcuenta" id="subcuenta" class='form-control text-black' disabled>
                    </select>
                </div>
            </div>

            <div id='factura-recibo' class='grid grid-cols-2 grid-rows-3'>
                <div class='mb-2 form-group'>
                    <label for="factura">Factura</label>
                    <input type="text" name='factura' id='factura' value="{{ old('factura') }}"
                        class='form-control'>
                </div>
                <div class='mb-2 form-group'>
                    <label for="recibo">Recibo</label>
                    <input type="text" name='recibo' id='recibo' value="{{ old('recibo') }}"
                        class='form-control'>
                </div>

                <div class='mb-2 col-span-2 w-full'>
                    <button class=' bg-gray-700 h-16 col-span-2 w-full text-white bold rounded '
                        id='habilitar' style='background-color:rgb(133, 133, 141) !important;'>Habilitar Factura / Recibo</button>
                </div>
                <div class='mb-2 col-span-2 w-full'>
                    <button type="submit" class=' bg-sky-900 h-16 col-span-2 w-full text-white bold rounded ' style='background-color:#0f1947 !important;'
                        id='Registrar'>Registrar Pago</button>
                </div>
            </div>
        </div>
    </div>
</form>
@stop

@section('css')
<style>
/* ==== Colores oficiales del modo oscuro de AdminLTE ==== */

/* Fondo global */
body, .wrapper, .content-wrapper {
    background-color: #343a40 !important; /* gris oscuro principal */
    color: #f8f9fa !important; /* texto claro */
}

/* Navbar (parte superior) */
.main-header {
    background-color: #212529 !important; /* tono más oscuro */
    color: #f8f9fa !important;
    border-bottom: 1px solid #495057 !important;
}

/* Sidebar (barra lateral izquierda) */
.main-sidebar {
    background-color: #23272b !important; /* fondo sidebar */
    color: #f8f9fa !important;
}

/* Fondo del logo / enlace de marca */
.brand-link {
    background-color: #1d2124 !important;
    color: #f8f9fa !important;
}

/* Links dentro del sidebar */
.nav-link {
    color: #c2c7d0 !important;
}

.nav-link.active, .nav-link:hover {
    background-color: #495057 !important;
    color: #fff !important;
}

/* Tarjetas (cards) */
.card {
    background-color: #2f353a !important;
    color: #fff !important;
    border: 1px solid #495057 !important;
}

/* Inputs y selects */
.form-control {
    background-color: #3b4045 !important;
    color: #fff !important;
    border: 1px solid #555b61 !important;
}

/* Labels */
label {
    color: #f8f9fa !important;
}

/* Botones */
button, .btn {
    background-color: #495057 !important;
    color: #fff !important;
    border: none !important;
    width:100%;
}

button:hover, .btn:hover {
    background-color: #6c757d !important;
}

/* Ajuste del fondo de alertas o formularios */
.swal2-popup {
    background-color: #2f353a !important;
    color: #fff !important;
}
/* Media Queries para responsividad */
@media (max-width: 768px) {
    .card {
        width: 95%;
        margin: 0 auto 1rem auto;
    }

    #saldo-container {
        width: 100% !important;
        float: none !important;
        margin-bottom: 1rem;
    }

    #fecha-importe, #cuentas, #factura-recibo {
        display: grid !important;
        grid-template-columns: 1fr !important;
        gap: 0.5rem;
    }

    #fecha-importe .form-group, 
    #cuentas .form-group, 
    #factura-recibo .form-group,
    #factura-recibo .col-span-2 {
        width: 100% !important;
    }

    #importe {
        text-align: right;
    }
}
</style>
@stop

@section('js')
<script>
    // Subcuentas dinámicas
    document.getElementById('cuenta').addEventListener('change', function() {
        const cuentaId = this.value;
        const subcuentaSelect = document.getElementById('subcuenta');
        subcuentaSelect.disabled = false;
        subcuentaSelect.innerHTML = '<option value="">Seleccione una subcuenta</option>';

        if (cuentaId) {
            fetch(`/subcuentas/${cuentaId}`)
                .then(res => res.json())
                .then(data => {
                    data.forEach(sc => {
                        const option = document.createElement('option');
                        option.value = sc.id;
                        option.textContent = sc.nombre;
                        subcuentaSelect.appendChild(option);
                    });
                }).catch(err => console.error(err));
        }
    });

    // Habilitar Factura / Recibo
    document.getElementById('habilitar').addEventListener('click', function(e) {
        e.preventDefault();
        const factura = document.getElementById('factura');
        const recibo = document.getElementById('recibo');
        factura.value = '';
        recibo.value = '';
        factura.disabled = false;
        recibo.disabled = false;
        factura.classList.add('border-blue-500');
        recibo.classList.add('border-blue-500');

        Swal.fire({
            icon: 'info',
            title: 'Campos habilitados',
            text: 'Ahora puedes editar la factura o el recibo.',
            timer: 2000,
            showConfirmButton: false
        });
    });

    // Solo números en Factura / Recibo
    function soloNumeros(event) {
        const key = event.key;
        if (!/[0-9]/.test(key) && !['Backspace', 'Tab', 'ArrowLeft', 'ArrowRight', 'Delete'].includes(key)) {
            event.preventDefault();
        }
    }
    document.getElementById('factura').addEventListener('keypress', soloNumeros);
    document.getElementById('recibo').addEventListener('keypress', soloNumeros);

    // Importe solo números y punto decimal, sin separador de miles
    const importeInput = document.getElementById('importe');
    const saldoSpan = document.getElementById('saldo');
    const saldoInicial = parseFloat(saldoSpan.textContent) || 0;

    importeInput.addEventListener('input', function() {
        let rawValue = this.value.replace(/[^0-9.]/g, '');
        const parts = rawValue.split('.');
        if (parts.length > 2) rawValue = parts[0] + '.' + parts.slice(1).join('');

        let importe = parseFloat(rawValue) || 0;
        let saldoActual = saldoInicial - importe;
        saldoSpan.textContent = saldoActual >= 0 ? saldoActual.toFixed(2) : '0.00';

        if (importe > saldoInicial) {
            importeInput.classList.add('border-red-500', 'text-red-600');
            Swal.fire({
                icon: 'error',
                title: 'Monto excedido',
                text: 'El importe ingresado supera el saldo disponible.',
                confirmButtonText: 'OK'
            }).then(() => {
                importeInput.value = '';
                saldoSpan.textContent = saldoInicial.toFixed(2);
                importeInput.classList.remove('border-red-500', 'text-red-600');
            });
        } else {
            importeInput.classList.remove('border-red-500', 'text-red-600');
        }

        this.value = rawValue;
    });

    importeInput.addEventListener('blur', function() {
        let importe = parseFloat(this.value) || 0;
        this.value = importe.toFixed(2);
    });

    document.querySelector('form').addEventListener('submit', function() {
        importeInput.value = importeInput.value.trim();
    });
</script>
@stop

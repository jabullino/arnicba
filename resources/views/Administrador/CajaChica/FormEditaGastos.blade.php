@extends('layouts.app')

@section('page_header')
    <h1>Editar Pago</h1>
@stop

@section('page_content')
    {{-- Mensajes SweetAlert --}}
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

    <div class="card w-[450px] mx-auto" >
        <div class="card-header font-bold text-center">
            FORMULARIO DE EDICIÓN DE PAGO
        </div>
        <div class="card-body">
            <form action="{{ route('gastoscajachica.update', $gasto->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div id='fechas' class='grid grid-cols-2'>
                    <div class="mb-3">
                        <label for="fecha_doc">Fecha Documento</label>
                        <input type="date" id="fecha_doc" name="fecha_doc" class="form-control"
                            value="{{ old('fecha_doc', $gasto->fecha_doc) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="importe">Importe</label>
                        <input type="number" step="0.01" id="importe" name="importe"
                            class="form-control text-right font-bold" value="{{ old('importe', $gasto->importe) }}" required>
                    </div>
                </div><!---fin div fechas -->

                <div id='documentos' class='grid grid-cols-2'>
                    <div class="mb-3">
                        <label for="factura">Factura</label>
                        <input type="text" id="factura" name="factura" class="form-control"
                            value="{{ old('factura', $gasto->factura) }}">
                    </div>

                    <div class="mb-3">
                        <label for="recibo">Recibo</label>
                        <input type="text" id="recibo" name="recibo" class="form-control"
                            value="{{ old('recibo', $gasto->recibo) }}">
                    </div>
                </div><!---fin div documentos --> 

                <div id='cuentas' class='grid grid-cols-2'>
                    <div class="mb-3">
                        <label for="cuenta">Cuenta</label>
                        <select id="cuenta" name="cuenta" class="form-control" required>
                            <option value="{{ $gasto->cuenta_id }}">{{ $nomcuenta }}</option>
                            @foreach ($cuentas as $cuenta)
                                @if($cuenta->id != $gasto->cuenta_id)
                                    <option value="{{ $cuenta->id }}" class="text-black">{{ $cuenta->nombre }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="subcuenta">Subcuenta</label>
                        <select id="subcuenta" name="subcuenta" class="form-control">
                            <option value="{{ $gasto->subcuenta_id }}">{{ $subcuenta ?? '—' }}</option>
                        </select>
                    </div>
                </div><!--fin div cuenta --->

                <div class="botones-flex mt-2">
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                    <a href="{{ route('gastoscajachica.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>

            {{-- Botón Eliminar --}}
            <form action="{{ route('gastoscajachica.destroy', $gasto->id) }}" method="POST" class="mt-3"
      onsubmit="return confirm('¿Seguro que deseas eliminar este registro?')">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-eliminar w-full">Eliminar Pago</button>
</form>
        </div>
    </div>
@stop

@section('css')
<style>
/* Fondo global */
body, .wrapper, .content-wrapper {
    background-color: #343a40 !important;
    color: #f8f9fa !important;
}

/* Navbar */
.main-header {
    background-color: #212529 !important;
    color: #f8f9fa !important;
    border-bottom: 1px solid #495057 !important;
}

/* Sidebar */
.main-sidebar {
    background-color: #23272b !important;
    color: #f8f9fa !important;
}

/* Logo / marca */
.brand-link {
    background-color: #1d2124 !important;
    color: #f8f9fa !important;
}

/* Links sidebar */
.nav-link {
    color: #c2c7d0 !important;
}
.nav-link.active, .nav-link:hover {
    background-color: #495057 !important;
    color: #fff !important;
}

/* Cards */
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

/* Botones globales */
button, .btn {
    background-color: #495057 !important;
    color: #fff !important;
    border: none !important;
}
button:hover, .btn:hover {
    background-color: #6c757d !important;
}

/* SweetAlert */
.swal2-popup {
    background-color: #2f353a !important;
    color: #fff !important;
}

/* ==== Colores botones específicos ==== */
.botones-flex .btn-primary {
    background-color: #0d6efd !important;
    color: #fff !important;
    border: none !important;
}
.botones-flex .btn-primary:hover {
    background-color: #0b5ed7 !important;
}

.botones-flex .btn-secondary {
    background-color: #6c757d !important;
    color: #fff !important;
    border: none !important;
}
.botones-flex .btn-secondary:hover {
    background-color: #5c636a !important;
}

/* Flex botones mitad cada uno */
.botones-flex {
    display: flex !important;
    gap: 0.5rem;
}
.botones-flex .btn {
    flex: 1 1 0 !important;
    width: 50% !important;
}

/* Botones tabla de gastos */
#resultado-gastos table#tablaGastos .btn-info {
    background-color: #0dcaf0 !important;
    color: #fff !important;
    border: none !important;
}
#resultado-gastos table#tablaGastos .btn-info:hover {
    background-color: #31d2f2 !important;
}

#resultado-gastos table#tablaGastos .btn-warning {
    background-color: #ffc107 !important;
    color: #212529 !important;
    border: none !important;
}
#resultado-gastos table#tablaGastos .btn-warning:hover {
    background-color: #e0a800 !important;
}

#resultado-gastos table#tablaGastos .btn-danger {
    background-color: #dc3545 !important;
    color: #fff !important;
    border: none !important;
}
#resultado-gastos table#tablaGastos .btn-danger:hover {
    background-color: #bb2d3b !important;
}
/* Botón rojo para eliminar */
.btn-eliminar {
    width: 100% !important;       /* Ocupa todo el ancho */
    background-color: #dc3545 !important; /* Rojo */
    color: #fff !important;       /* Texto blanco */
    border: none !important;
    padding: 0.5rem 1rem;         /* Ajusta altura y relleno */
    font-weight: bold;
    border-radius: 0.25rem;       /* Opcional: bordes ligeramente redondeados */
    cursor: pointer;
    transition: background-color 0.2s ease-in-out;
}

.btn-eliminar:hover {
    background-color: #bb2d3b !important; /* Rojo más oscuro al pasar el mouse */
}

/* Media Queries para pantallas pequeñas */
@media (max-width: 768px) {
    .card.w-[450px] {
        width: 95%;
        margin: 0 auto;
    }

    #fechas, #documentos, #cuentas {
        display: block !important;
    }

    #fechas .mb-3,
    #documentos .mb-3,
    #cuentas .mb-3 {
        width: 100%;
        margin-bottom: 1rem;
    }

    .botones-flex {
        display: block !important;
    }

    .botones-flex .btn {
        width: 100% !important;
        margin-bottom: 0.5rem;
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
                })
                .catch(err => console.error(err));
        }
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const importeInput = document.getElementById('importe');
        const form = document.querySelector('form[action*="update"]');

        const saldoDisponible = {{ $disponible ?? 0 }};
        const importeOriginal = {{ $gasto->importe }};

        form.addEventListener('submit', function(event) {
            const nuevoImporte = parseFloat(importeInput.value) || 0;
            const diferencia = nuevoImporte - importeOriginal;
            let saldoFinal = saldoDisponible;

            if (diferencia > 0) saldoFinal -= diferencia;
            else if (diferencia < 0) saldoFinal += Math.abs(diferencia);

            if (saldoFinal < 0) {
                event.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Saldo insuficiente',
                    text: 'El importe ingresado excede el saldo disponible.',
                    confirmButtonText: 'Entendido',
                }).then(() => {
                    importeInput.value = importeOriginal.toFixed(2);
                });
            }
        });
    });
</script>
@stop

@extends('layouts.app')

@section('content')

<form action="{{ route('incrementosalarial.store') }}" method="POST">
    @csrf

<div class="container">

    <h3>Incremento Salarial</h3>

    <!-- Gestión -->
    <div class="mb-3">
        <label>Gestión:</label>
        <select class="form-control" name="gestion_id" required>
            @foreach($gestiones as $gestion)
                <option value="{{ $gestion->id }}">
                    {{ $gestion->nombre }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Inputs -->
    <div class="row mb-3">
        <div class="col-md-6">
            <label>Incremento (%):</label>
            <input type="text" name="incremento" id="incremento" class="form-control" required>
        </div>

        <div class="col-md-6">
            <label>Salario Mínimo:</label>
            <input type="text" name="salario_minimo" id="salario_minimo" class="form-control" required>
        </div>
    </div>

    <!-- Botón -->
    <div class="mb-3">
        <button type="submit" class="btn btn-primary">
            💾 Registrar Incremento
        </button>
    </div>

    <!-- Tabla -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th><input type="checkbox" id="checkAll"></th>
                <th>Cargo</th>
                <th>Salario Actual</th>
                <th>Nuevo Salario (Vista Previa)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($haberes as $item)
                <tr>
                    <td>
                        <input type="checkbox" 
                               name="cargos[]" 
                               class="checkItem" 
                               value="{{ $item->cargo_id }}">
                    </td>
                    <td>{{ $item->cargo_nombre }}</td>
                    <td class="salario-actual">{{ $item->monto }}</td>
                    <td class="nuevo-salario">-</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>
</form>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Éxito',
    text: '{{ session("success") }}'
});
</script>
@endif

@if(session('error'))
<script>
Swal.fire({
    icon: 'error',
    title: 'Error',
    text: '{{ session("error") }}'
});
</script>
@endif

@if($errors->any())
<script>
Swal.fire({
    icon: 'error',
    title: 'Error',
    text: '{{ $errors->first() }}'
});
</script>
@endif

<!-- Scripts -->
<script>

// 🔹 Seleccionar todos
document.getElementById('checkAll').addEventListener('change', function() {
    document.querySelectorAll('.checkItem').forEach(cb => cb.checked = this.checked);
});

// 🔹 Validación decimal
function soloNumerosDecimal(input) {
    input.addEventListener('input', function () {
        this.value = this.value.replace(/[^0-9.]/g, '');
        let partes = this.value.split('.');
        if (partes.length > 2) {
            this.value = partes[0] + '.' + partes[1];
        }
    });
}

const incrementoInput = document.getElementById('incremento');
const salarioMinInput = document.getElementById('salario_minimo');

soloNumerosDecimal(incrementoInput);
soloNumerosDecimal(salarioMinInput);

// 🔥 FUNCIÓN DE PREVIEW
function calcularPreview() {

    let incremento = parseFloat(incrementoInput.value) || 0;
    let salarioMin = parseFloat(salarioMinInput.value) || 0;

    document.querySelectorAll('tbody tr').forEach(row => {

        let salarioActual = parseFloat(row.querySelector('.salario-actual').innerText);
        let nuevo = salarioActual + (salarioActual * (incremento / 100));

        // aplicar salario mínimo
        if (nuevo < salarioMin) {
            nuevo = salarioMin;
        }

        row.querySelector('.nuevo-salario').innerText = nuevo.toFixed(2);
    });
}

// 🔹 Eventos en tiempo real
incrementoInput.addEventListener('input', calcularPreview);
salarioMinInput.addEventListener('input', calcularPreview);

</script>

@endsection
@extends('layouts.app')
@section('content')

<style>
    /* Media queries para responsive */
    @media (max-width: 1024px) {
        .table {
            width: 100% !important;
            display: block;
            overflow-x: auto;
        }
        .form-control, button {
            width: 100% !important;
        }
    }

    @media (max-width: 640px) {
        .table th, .table td {
            font-size: 0.875rem;
            padding: 4px 6px;
        }
        button {
            font-size: 0.875rem;
            height: 40px !important;
        }

    }
  /* Ajuste visual del select dentro del td */
.select-cell {
    padding: 0 !important;
    height: auto !important;
    vertical-align: middle !important;
}

/* Hace que el select llene completamente el td */
.select-cell select {
    width: 100% !important;
    height: 100% !important;
    box-sizing: border-box !important;
    margin: 0 !important;
    border: none !important;
    outline: none !important;
    color: black !important;
    background-color: white !important;
    font-size: 0.9rem !important;
}

/* Si quieres que el alto del select sea igual al de los input.form-control */
.table td,
.table th {
    padding-top: 4px !important;
    padding-bottom: 4px !important;
}

</style>

@if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: "{{ session('success') }}",
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Aceptar'
        });
    </script>
@endif

@if (session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: '¡Error!',
            text: "{{ session('error') }}",
            confirmButtonColor: '#d33',
            confirmButtonText: 'Aceptar'
        });
    </script>
@endif

@if ($errors->any())
    <script>
        let errores = `
        <ul style="text-align:left;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>`;
        Swal.fire({
            icon: 'error',
            title: 'Existen errores en su formulario:',
            html: errores,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Corregir'
        });
    </script>
@endif


<form action="{{ route('pagaservicios') }}" method="POST" id="formpagoservicios">
    @csrf
    <div class='bg-sky-900 text-center text-white text-bold w-full'>
        FORMULARIO PARA PAGO RÁPIDO DE SERVICIOS
    </div>

    <table id="dynamicTable" border="1" cellpadding="10" class='table table-striped'>
        <thead>
            <tr>
                <th class='text-center'>#</th>
                <th class='text-center'>Fecha</th>
                <th class='text-center'>Factura</th>
                <th class='text-center'>Monto Bs.</th>
                <th class='text-center'>Opción</th>
                <th class='text-center'>Acciones</th>
            </tr>
        </thead>
        <tbody id="tableBody">
            <!-- Filas se insertarán dinámicamente -->
        </tbody>
    </table>

    <button type="button" onclick="addRow()" class='bg-gray-700 text-white text-bold text-lg rounded-md w-full h-12 form-control'>Añadir Servicio</button>
    <br><br>
    <button type="submit" class='bg-sky-700 text-white text-bold text-lg rounded-md w-full h-12 form-control'>Guardar Asientos de Diario</button>
</form>

@section('js')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById('formpagoservicios');

    form.addEventListener('submit', function (e) {
        let isValid = true;
        let errores = [];
        const today = new Date();
        today.setHours(0,0,0,0);

        // Validar data[fecha]
        form.querySelectorAll('input[name^="data["][name$="[fecha]"]').forEach(input => {
            const inputDate = new Date(input.value);
            inputDate.setHours(0,0,0,0);

            if (!input.value || isNaN(inputDate.getTime())) {
                isValid = false;
                errores.push("Fecha inválida.");
                input.classList.add('is-invalid');
            } else if (inputDate > today) {
                isValid = false;
                errores.push("La fecha no puede ser mayor a la actual.");
                input.classList.add('is-invalid');
            } else {
                input.classList.remove('is-invalid');
            }
        });

        // Validar data[factura]
        form.querySelectorAll('input[name^="data["][name$="[factura]"]').forEach(input => {
            if (!/^\d+$/.test(input.value)) {
                isValid = false;
                errores.push("Factura debe contener solo números.");
                input.classList.add('is-invalid');
            } else {
                input.classList.remove('is-invalid');
            }
        });

        // Validar data[valor]
        form.querySelectorAll('input[name^="data["][name$="[valor]"]').forEach(input => {
            if (!/^\d+(\.\d{1,2})?$/.test(input.value)) {
                isValid = false;
                errores.push("Valor debe ser un número decimal válido.");
                input.classList.add('is-invalid');
            } else {
                input.classList.remove('is-invalid');
            }
        });

        // Validar data[opcion_id]
        form.querySelectorAll('select[name^="data["][name$="[opcion_id]"]').forEach(select => {
            if (!select.value || select.value === '0') {
                isValid = false;
                errores.push("Debe seleccionar una opción válida.");
                select.classList.add('is-invalid');
            } else {
                select.classList.remove('is-invalid');
            }
        });

        if (!isValid) {
            e.preventDefault();
            alert("Corrige los siguientes errores:\n\n" + [...new Set(errores)].join("\n"));
        }
    });
});

let rowIndex = 0;
let optionsData = [];

async function fetchOptions() {
    const response = await fetch('api/options');
    optionsData = await response.json();
}

function createSelect(name) {
    const select = document.createElement("select");
    select.name = name;
    select.required = true;
    optionsData.forEach(opt => {
        const option = document.createElement("option");
        option.value = opt.id;
        option.textContent = opt.nombre;
        select.appendChild(option);
    });
    return select;
}

function addRow() {
    const tableBody = document.getElementById("tableBody");
    const row = document.createElement("tr");
    row.setAttribute("data-index", rowIndex);

    row.innerHTML = `
        <td class="row-index">${rowIndex + 1}</td>
        <td><input type="date" name="data[${rowIndex}][fecha]" required class='form-control'></td>
        <td><input type="text" name="data[${rowIndex}][factura]" required class='form-control text-right'></td>
        <td><input type="text" name="data[${rowIndex}][valor]" required class='form-control text-right'></td>
        <td class="select-cell text-black" 
    style="color:black !important; background-color:white !important; width:80px !important; padding:0 !important;"></td>
        <td><button type="button" onclick="removeRow(this)" class="bg-red-700 text-white bold text-md text-lg form-control rounded-md">Eliminar</button></td>
    `;

    const selectCell = row.querySelector(".select-cell");
    const select = createSelect(`data[${rowIndex}][opcion_id]`);
    selectCell.appendChild(select);

    tableBody.appendChild(row);
    rowIndex++;
}

function removeRow(button) {
    const row = button.closest("tr");
    row.remove();
    updateIndices();
}

function updateIndices() {
    const rows = document.querySelectorAll("#tableBody tr");
    rowIndex = 0;
    rows.forEach((row, index) => {
        row.setAttribute("data-index", index);
        row.querySelector(".row-index").textContent = index + 1;

        const fechaInput = row.querySelector('input[type="date"]');
        const facturaInput = row.querySelector('input[name$="[factura]"]');
        const valorInput = row.querySelector('input[name$="[valor]"]');
        const select = row.querySelector("select");

        fechaInput.name = `data[${index}][fecha]`;
        facturaInput.name = `data[${index}][factura]`;
        valorInput.name = `data[${index}][valor]`;
        select.name = `data[${index}][opcion_id]`;

        rowIndex = index + 1;
    });
}

document.addEventListener("DOMContentLoaded", fetchOptions);
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Función para validar enteros
    function validarEnteros(input) {
        input.addEventListener('input', function() {
            // Elimina cualquier carácter que no sea dígito
            this.value = this.value.replace(/\D/g, '');
        });
    }

    // Función para validar decimales con punto
    function validarDecimales(input) {
        input.addEventListener('input', function() {
            // Permite solo números y un punto decimal
            this.value = this.value.replace(/[^0-9.]/g, '');
            // Evita que haya más de un punto
            const partes = this.value.split('.');
            if (partes.length > 2) {
                this.value = partes[0] + '.' + partes[1];
            }
        });
    }

    // Aplica las validaciones a los campos existentes y futuros
    function aplicarValidaciones() {
        // Factura -> solo enteros
        document.querySelectorAll('input[name^="data"][name$="[factura]"]').forEach(input => {
            validarEnteros(input);
        });

        // Valor -> solo números con punto decimal
        document.querySelectorAll('input[name^="data"][name$="[valor]"]').forEach(input => {
            validarDecimales(input);
        });
    }

    aplicarValidaciones();

    // Si agregas filas dinámicamente, re-aplica las validaciones
    document.body.addEventListener('input', function(e) {
        if (e.target.matches('input[name^="data"][name$="[factura]"]')) {
            validarEnteros(e.target);
        }
        if (e.target.matches('input[name^="data"][name$="[valor]"]')) {
            validarDecimales(e.target);
        }
    });
});
</script>

@endsection

@endsection

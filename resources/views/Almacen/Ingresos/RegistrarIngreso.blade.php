@extends('layouts.app')

@section('content')
<div class="container-fluid px-2 px-md-4">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">

            <div class="card shadow-lg border-0">
                <div class="card-header text-white text-center py-3"
                    style="background-color:#134e4a;">
                    <h5 class="mb-0">FORMULARIO DE REGISTRO DE INGRESO</h5>
                </div>

                <div class="card-body p-3 p-md-4">

                    <form method="POST" action="{{ route('Ingresos.store') }}" id="formIngreso">
                        @csrf

                        {{-- ENCABEZADO --}}
                        <div class="row g-3 mb-4">

                            <div class="col-12 col-md-6 col-lg-3">
                                <label class="form-label">Fecha</label>
                                <input type="date" name="fecha" class="form-control" required>
                            </div>

                            <div class="col-12 col-md-6 col-lg-3">
                                <label class="form-label">Factura</label>
                                <input type="text" name="factura" id="factura" class="form-control">
                            </div>

                            <div class="col-12 col-md-6 col-lg-3">
                                <label class="form-label">Recibo</label>
                                <input type="text" name="recibo" id="recibo" class="form-control">
                            </div>

                            <div class="col-12 col-md-6 col-lg-3 d-flex align-items-end">
                                <button type="button" class="btn btn-secondary w-100"
                                    onclick="resetDocumento()">
                                    Restablecer Documento
                                </button>
                            </div>

                            <div class="col-12 col-md-6 col-lg-3">
                                <label class="form-label">Origen de Fondos</label>
                                <select name="origen_fondo_id" class="form-control" required>
                                    <option value="">Seleccione</option>
                                    @foreach ($origenFondos as $origen)
                                        <option value="{{ $origen->id }}">
                                            {{ $origen->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                        {{-- BUSCADOR --}}
                        <div class="mb-4 position-relative overflow-visible">
                            <label class="form-label">Buscar Producto</label>
                            <input type="text" id="buscarProducto"
                                class="form-control form-control-lg"
                                placeholder="Buscar por nombre, código o marca"
                                autocomplete="off">

                            <input type="hidden" id="producto_id">

                            <div id="resultadoProductos"
                                class="list-group position-absolute w-100 shadow"
                                style="z-index:1000; display:none;">
                            </div>
                        </div>

                        {{-- CANTIDAD, PRECIO Y FECHA VENCIMIENTO --}}
                        <div class="row g-3 mb-4">

                            <div class="col-12 col-md-3">
                                <label class="form-label">Cantidad</label>
                                <input type="number" id="cantidad"
                                    class="form-control form-control-lg">
                            </div>

                            <div class="col-12 col-md-3">
                                <label class="form-label">Precio</label>
                                <input type="number" step="0.01" id="precio"
                                    class="form-control form-control-lg">
                            </div>

                            <div class="col-12 col-md-3">
                                <label class="form-label">Fecha de vencimiento (opcional)</label>
                                <input type="date" id="fecha_vencimiento"
                                    class="form-control form-control-lg">
                            </div>

                            <div class="col-12 col-md-3 d-grid">
                                <button type="button"
                                    class="btn btn-lg text-white"
                                    style="background-color:#134e4a;"
                                    onclick="agregarProducto()">
                                    AGREGAR AL CARRITO
                                </button>
                            </div>

                        </div>

                        {{-- TABLA --}}
                        <div class="table-responsive mb-3">
                            <table class="table table-hover table-bordered align-middle text-center"
                                id="tabla-detalle">
                                <thead style="background-color:#134e4a; color:white;">
                                    <tr>
                                        <th>Cantidad</th>
                                        <th>Producto</th>
                                        <th>Precio</th>
                                        <th>Fecha Vencimiento</th>
                                        <th>Eliminar</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>

                        <div class="d-grid">
                            <button type="submit"
                                class="btn btn-lg text-white"
                                style="background-color:#134e4a;">
                                GUARDAR INGRESO
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('js')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

let facturaInput = document.getElementById('factura');
let reciboInput = document.getElementById('recibo');

/* SOLO NÚMEROS */
function soloNumeros(e) {
    e.target.value = e.target.value.replace(/[^0-9]/g, '');
}

facturaInput.addEventListener('input', soloNumeros);
reciboInput.addEventListener('input', soloNumeros);

/* BLOQUEO MUTUO */
facturaInput.addEventListener('input', function() {
    if (this.value.trim() !== '') {
        reciboInput.disabled = true;
    } else {
        reciboInput.disabled = false;
    }
});

reciboInput.addEventListener('input', function() {
    if (this.value.trim() !== '') {
        facturaInput.disabled = true;
    } else {
        facturaInput.disabled = false;
    }
});

/* RESTABLECER */
function resetDocumento() {
    facturaInput.value = '';
    reciboInput.value = '';
    facturaInput.disabled = false;
    reciboInput.disabled = false;
}

/* ================= RESTO ORIGINAL ================= */

let timeout = null;
let index = 0;

/* BUSCADOR */
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
                    option.classList.add('list-group-item','list-group-item-action');
                    option.innerText = item.nombre;

                    option.onclick = () => {
                        document.getElementById('buscarProducto').value = item.nombre;
                        document.getElementById('producto_id').value = item.id;
                        resultados.style.display = 'none';
                    };

                    resultados.appendChild(option);
                });

                resultados.style.display = 'block';
            });

    }, 300);
});

/* AGREGAR PRODUCTO */
function agregarProducto(){

    let productoId = document.getElementById('producto_id').value;
    let nombre = document.getElementById('buscarProducto').value;
    let cantidad = document.getElementById('cantidad').value;
    let precio = document.getElementById('precio').value;
    let fechaVencimiento = document.getElementById('fecha_vencimiento').value;

    if(!productoId || cantidad <= 0 || precio <= 0){
        Swal.fire({
            icon:'warning',
            title:'Datos incompletos',
            text:'Seleccione producto, cantidad y precio válidos.',
            confirmButtonColor:'#134e4a'
        });
        return;
    }

    let fila = `
        <tr id="fila-${index}">
            <td>
                ${cantidad}
                <input type="hidden" name="detalles[${index}][producto_id]" value="${productoId}">
                <input type="hidden" name="detalles[${index}][cantidad]" value="${cantidad}">
                <input type="hidden" name="detalles[${index}][precio]" value="${precio}">
                <input type="hidden" name="detalles[${index}][fecha_vencimiento]" value="${fechaVencimiento}">
            </td>
            <td>${nombre}</td>
            <td>${precio}</td>
            <td>${fechaVencimiento ? fechaVencimiento : '-'}</td>
            <td>
                <button type="button" class="btn btn-danger btn-sm"
                    onclick="eliminarFila(${index})">
                    X
                </button>
            </td>
        </tr>
    `;

    document.querySelector('#tabla-detalle tbody')
        .insertAdjacentHTML('beforeend', fila);

    index++;

    document.getElementById('buscarProducto').value = '';
    document.getElementById('producto_id').value = '';
    document.getElementById('cantidad').value = '';
    document.getElementById('precio').value = '';
    document.getElementById('fecha_vencimiento').value = '';
}

/* ELIMINAR */
function eliminarFila(i){
    Swal.fire({
        title: '¿Eliminar producto?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#134e4a',
        confirmButtonText: 'Sí, eliminar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('fila-'+i).remove();
        }
    });
}

/* CONFIRMAR ENVÍO */
document.getElementById('formIngreso').addEventListener('submit', function(e){

    if(document.querySelectorAll('#tabla-detalle tbody tr').length === 0){
        e.preventDefault();
        Swal.fire({
            icon:'warning',
            title:'Carrito vacío',
            text:'Debe agregar al menos un producto.',
            confirmButtonColor:'#134e4a'
        });
        return;
    }

    e.preventDefault();

    Swal.fire({
        title: '¿Guardar ingreso?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#134e4a',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, guardar'
    }).then((result) => {
        if (result.isConfirmed) {
            this.submit();
        }
    });
});

</script>

@endsection
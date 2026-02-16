@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">

            <div class="card shadow">
                <div class="card-header text-white text-center"
                     style="background-color:#134e4a;">
                    <strong>FORMULARIO DE EGRESO DE PRODUCTOS</strong>
                </div>

                <div class="card-body">

                    <form method="POST" action="{{ route('Egresos.store') }}" id="formEgreso">
                        @csrf

                        {{-- DESTINATARIO --}}
                        <div class="form-group mb-3">
                            <label for="destinatario">Destinatario</label>
                            <select name="destinatario" id="destinatario" class="form-control">
                                <option value="default">Escoja un destinatario</option>
                                @foreach ($destinatarios as $dest)
                                    <option value="{{$dest->id}}">{{$dest->nombre}}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- BUSCADOR --}}
                        <div class="form-group mb-3 position-relative overflow-visible">
                            <label>Producto</label>

                            <input type="text"
                                   id="buscarProducto"
                                   class="form-control"
                                   placeholder="Buscar por nombre, código o marca"
                                   autocomplete="off">

                            <input type="hidden" id="producto_id">

                            <div id="resultadoProductos"
                                 class="list-group position-absolute w-100"
                                 style="z-index:1000; display:none;">
                            </div>
                        </div>

                        {{-- INFO + CANTIDAD --}}
                        <div id="infoProducto" class="alert alert-info" style="display:none;">

                            <div class="row align-items-end g-2">

                                <div class="col-12 col-md-4">
                                    <strong>Producto:</strong><br>
                                    <span id="productoNombre"></span>
                                </div>

                                <div class="col-6 col-md-2">
                                    <strong>Disponible:</strong><br>
                                    <span id="productoSaldo"></span>
                                </div>

                                <div class="col-6 col-md-3">
                                    <label>Cantidad</label>
                                    <input type="number"
                                           id="cantidadRetiro"
                                           class="form-control"
                                           min="1"
                                           placeholder="0">
                                </div>

                                <div class="col-12 col-md-3">
                                    <button type="button"
                                            id="btnAgregar"
                                            class="btn btn-success w-100 mt-2 mt-md-0">
                                        <i class="fas fa-cart-plus"></i> Añadir
                                    </button>
                                </div>

                            </div>
                        </div>

                        {{-- CARRITO --}}
                        <div class="card mt-4" id="carritoCard" style="display:none;">
                            <div class="card-header bg-dark text-white">
                                🛒 Productos agregados
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Producto</th>
                                            <th width="120">Cantidad</th>
                                            <th width="80">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody id="carritoBody"></tbody>
                                </table>
                            </div>
                        </div>

                        <input type="hidden" name="carrito" id="carritoInput">

                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary btn-lg w-100 w-md-auto">
                                Guardar Egreso
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
let timeout = null;
let carrito = [];

/* BUSCAR PRODUCTO */
document.getElementById('buscarProducto').addEventListener('keyup', function () {

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
                    option.innerText = item.nombre + " (Stock: " + item.saldo + ")";

                    option.onclick = () => {

                        document.getElementById('buscarProducto').value = item.nombre;
                        document.getElementById('producto_id').value = item.id;
                        document.getElementById('productoNombre').innerText = item.nombre;
                        document.getElementById('productoSaldo').innerText = item.saldo;

                        document.getElementById('infoProducto').style.display = 'block';
                        resultados.style.display = 'none';
                    };

                    resultados.appendChild(option);
                });

                resultados.style.display = 'block';
            });

    }, 300);
});


/* AGREGAR */
document.getElementById('btnAgregar').addEventListener('click', function () {

    const id = document.getElementById('producto_id').value;
    const nombre = document.getElementById('productoNombre').innerText;
    const saldo = parseInt(document.getElementById('productoSaldo').innerText);
    const cantidad = parseInt(document.getElementById('cantidadRetiro').value);

    if (!id) {
        Swal.fire('Error','Seleccione un producto','warning');
        return;
    }

    if (!cantidad || cantidad <= 0) {
        Swal.fire('Error','Ingrese cantidad válida','warning');
        return;
    }

    if (cantidad > saldo) {
        Swal.fire('Error','Stock insuficiente','error');
        return;
    }

    carrito.push({ id, nombre, cantidad });

    renderCarrito();
    document.getElementById('cantidadRetiro').value = '';
});


/* RENDER */
function renderCarrito() {

    const tbody = document.getElementById('carritoBody');
    const card = document.getElementById('carritoCard');

    tbody.innerHTML = '';

    if (carrito.length === 0) {
        card.style.display = 'none';
        return;
    }

    card.style.display = 'block';

    carrito.forEach((item, index) => {

        tbody.innerHTML += `
            <tr>
                <td>${item.nombre}</td>
                <td>${item.cantidad}</td>
                <td>
                    <button type="button"
                            class="btn btn-danger btn-sm"
                            onclick="eliminarItem(${index})">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    });

    document.getElementById('carritoInput').value = JSON.stringify(carrito);
}

function eliminarItem(index) {
    carrito.splice(index, 1);
    renderCarrito();
}

document.getElementById('formEgreso').addEventListener('submit', function(e) {
    if (carrito.length === 0) {
        e.preventDefault();
        Swal.fire('Error','Debe agregar al menos un producto','warning');
    }
});
</script>

{{-- SWEET ALERT RESPUESTAS DEL BACKEND --}}
@if(session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Éxito',
    text: "{{ session('success') }}",
    confirmButtonColor: '#134e4a'
});
</script>
@endif

@if(session('error'))
<script>
Swal.fire({
    icon: 'error',
    title: 'Error',
    text: "{{ session('error') }}",
    confirmButtonColor: '#134e4a'
});
</script>
@endif

@endsection


@push('styles')
<style>
.overflow-visible {
    overflow: visible !important;
}

#resultadoProductos {
    background-color: #1f2937 !important;
    border: 1px solid #374151 !important;
}

#resultadoProductos .list-group-item {
    background-color: #1f2937 !important;
    color: #e5e7eb !important;
    border: none !important;
}

#resultadoProductos .list-group-item-action:hover {
    background-color: #374151 !important;
    color: #ffffff !important;
}
</style>
@endpush
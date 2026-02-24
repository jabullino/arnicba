@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 col-xl-8">

            <div class="card shadow-sm">
                <div class="card-header bg-teal-900  text-white text-center text-md-start">
                    <h5 class="mb-0">
                        EDITAR EGRESO #{{ $egreso->id }}
                    </h5>
                </div>

                <div class="card-body">

                    <form method="POST"
                          action="{{ route('Egresos.update', $egreso->id) }}"
                          id="formEgreso">
                        @csrf
                        @method('PUT')

                        {{-- DESTINATARIO --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                Destinatario
                            </label>
                            <select name="destinatario"
                                    class="form-select"
                                    required>
                                @foreach($destinatarios as $dest)
                                    <option value="{{ $dest->id }}"
                                        {{ $dest->id == $egreso->destinatario_id ? 'selected' : '' }}>
                                        {{ $dest->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- PRODUCTOS --}}
                        <h6 class="mt-4 mb-3 fw-semibold">
                            Productos
                        </h6>

                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead class="table-light text-center">
                                    <tr>
                                        <th>Producto</th>
                                        <th style="min-width:110px;">Cantidad</th>
                                        <th style="min-width:80px;">Acción</th>
                                    </tr>
                                </thead>
                                <tbody id="carritoBody"></tbody>
                            </table>
                        </div>

                        <input type="hidden" name="carrito" id="carritoInput">

                        <div class="d-grid d-md-flex justify-content-md-end mt-4">
                            <button type="submit"
                                    class="btn btn-primary px-4">
                                Actualizar Egreso
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
<script>
let carrito = [];

// 🔹 Precargar productos existentes
@foreach($egreso->detalles as $detalle)
    carrito.push({
        id: {{ $detalle->producto->id }},
        nombre: "{{ $detalle->producto->nombre }}",
        cantidad: {{ $detalle->cantidad }}
    });
@endforeach

renderCarrito();

function renderCarrito() {

    const tbody = document.getElementById('carritoBody');
    tbody.innerHTML = '';

    carrito.forEach((item, index) => {

        tbody.innerHTML += `
            <tr class="text-center text-md-start">
                <td class="fw-medium">${item.nombre}</td>
                <td>
                    <input type="number"
                           value="${item.cantidad}"
                           min="1"
                           class="form-control text-center"
                           onchange="actualizarCantidad(${index}, this.value)">
                </td>
                <td>
                    <button type="button"
                            class="btn btn-danger btn-sm w-100 w-md-auto"
                            onclick="eliminarItem(${index})">
                        X
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

function actualizarCantidad(index, cantidad) {
    carrito[index].cantidad = parseInt(cantidad);
    renderCarrito();
}

document.getElementById('formEgreso').addEventListener('submit', function(e) {
    if (carrito.length === 0) {
        e.preventDefault();
        alert('Debe haber al menos un producto.');
    }
});
</script>
@endsection
@extends('layouts.app')

@section('content')
<div class="container-fluid px-2 px-md-4">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">

            <div class="card shadow-lg border-0">

                <div class="card-header text-white text-center py-3"
                     style="background-color:#134e4a;">
                    <h5 class="mb-0">
                        EDITAR INGRESO #{{ $ingreso->id }}
                    </h5>
                </div>

                <div class="card-body">

                    <form method="POST"
                          action="{{ route('Ingresos.update', $ingreso->id) }}">
                        @csrf
                        @method('PUT')

                        {{-- ENCABEZADO --}}
                        <div class="row g-3 mb-4">

                            <div class="col-md-3">
                                <label class="form-label">Fecha</label>
                                <input type="date"
                                       name="fecha"
                                       class="form-control"
                                       value="{{ $ingreso->fecha }}"
                                       required>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Factura</label>
                                <input type="text"
                                       name="factura"
                                       class="form-control"
                                       value="{{ $ingreso->factura }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Recibo</label>
                                <input type="text"
                                       name="recibo"
                                       class="form-control"
                                       value="{{ $ingreso->recibo }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Origen</label>
                                <select name="origen_fondo_id"
                                        class="form-control"
                                        required>
                                    @foreach($origenFondos as $origen)
                                        <option value="{{ $origen->id }}"
                                            {{ $origen->id == $ingreso->origen_id ? 'selected' : '' }}>
                                            {{ $origen->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                        {{-- DETALLE --}}
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered text-center align-middle">

                                <thead style="background-color:#134e4a; color:white;">
                                    <tr>
                                        <th>Cantidad</th>
                                        <th>Producto</th>
                                        <th>Precio</th>
                                        <th>Fecha Vencimiento</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($ingreso->detalles as $index => $detalle)
                                        <tr>

                                            <td>
                                                <input type="number"
                                                       name="detalles[{{ $index }}][cantidad]"
                                                       class="form-control"
                                                       value="{{ $detalle->cantidad }}"
                                                       min="0.01"
                                                       step="0.01"
                                                       required>
                                            </td>

                                            <td>
                                                {{ $detalle->producto->nombre }}
                                                <input type="hidden"
                                                       name="detalles[{{ $index }}][producto_id]"
                                                       value="{{ $detalle->producto_id }}">
                                            </td>

                                            <td>
                                                <input type="number"
                                                       name="detalles[{{ $index }}][precio]"
                                                       class="form-control"
                                                       value="{{ $detalle->precio }}"
                                                       step="0.01"
                                                       required>
                                            </td>

                                            <td>
                                                <input type="date"
                                                       name="detalles[{{ $index }}][fecha_vencimiento]"
                                                       class="form-control"
                                                       value="{{ $detalle->vencimiento }}">
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>

                        <div class="d-grid">
                            <button type="submit"
                                    class="btn btn-lg text-white"
                                    style="background-color:#134e4a;">
                                ACTUALIZAR INGRESO
                            </button>
                        </div>

                    </form>

                </div>

            </div>
        </div>
    </div>
</div>
@endsection
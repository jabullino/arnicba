@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">

            <div class="card shadow">
                <div class="card-header text-white text-center"
                     style="background-color:#134e4a;">
                    <strong>EDITAR LOTE</strong>
                </div>

                <div class="card-body">

                    <form method="POST" action="{{ route('Lote.update', $lote->id) }}">
                        @csrf
                        @method('PUT')

                        {{-- INFORMACIÓN DEL PRODUCTO --}}
                        <div class="alert alert-info">
                            <strong>Producto:</strong><br>
                            {{ $lote->producto_codigo }}
                            {{ $lote->producto_nombre }}
                            {{ $lote->producto_marca }}
                            <br>
                            <strong>Saldo actual:</strong> {{ $lote->saldo }}
                        </div>

                        <div class="row">

                            <div class="col-12 col-md-4 mb-3">
                                <label>Código de Lote</label>
                                <input type="text"
                                       name="codigo"
                                       value="{{ old('codigo', $lote->codigo) }}"
                                       class="form-control"
                                       required>
                            </div>

                            <div class="col-12 col-md-4 mb-3">
                                <label>Cantidad</label>
                                <input type="number"
                                       step="0.01"
                                       name="cantidad"
                                       value="{{ old('cantidad', $lote->cantidad) }}"
                                       class="form-control"
                                       required>
                            </div>

                            <div class="col-12 col-md-4 mb-3">
                                <label>Precio Unitario</label>
                                <input type="number"
                                       step="0.01"
                                       name="precio"
                                       value="{{ old('precio', $lote->precio) }}"
                                       class="form-control"
                                       required>
                            </div>

                            <div class="col-12 col-md-6 mb-3">
                                <label>Fecha Vencimiento</label>
                                <input type="date"
                                       name="fec_venc"
                                       value="{{ old('fec_venc', $lote->fec_venc) }}"
                                       class="form-control">
                            </div>

                            <div class="col-12 col-md-6 mb-3">
                                <label>Origen</label>
                                <select name="origen_id"
                                        class="form-control"
                                        required>
                                    @foreach($origenes as $origen)
                                        <option value="{{ $origen->id }}"
                                            {{ old('origen_id', $lote->origen_id) == $origen->id ? 'selected' : '' }}>
                                            {{ $origen->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- BOTONES --}}
                            <div class="col-12 mt-3 d-flex gap-2 flex-column flex-md-row">
                                
                                <button type="submit"
                                        class="btn btn-lg w-100 text-white"
                                        style="background-color:#134e4a;">
                                    ACTUALIZAR LOTE
                                </button>

                                <a href="{{ route('Lote.index') }}"
                                   class="btn btn-lg btn-secondary w-100">
                                   CANCELAR
                                </a>

                            </div>

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

@if(session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Actualizado',
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

@if ($errors->any())
<script>
Swal.fire({
    icon: 'warning',
    title: 'Errores de validación',
    html: `{!! implode('<br>', $errors->all()) !!}`,
    confirmButtonColor: '#134e4a'
});
</script>
@endif

@endsection


@push('styles')
<style>

@media (max-width: 768px) {

    .card {
        margin: 10px;
    }

    .card-header strong {
        font-size: 14px;
    }

    .btn-lg {
        font-size: 16px;
        padding: 12px;
    }

    .form-control {
        font-size: 14px;
    }

}

</style>
@endpush
@extends('layouts.app')

@section('page_header')
    <h1>Detalle del Pago</h1>
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

    {{-- Card con detalle del pago --}}
    <div class="card mt-4">
        <div class="card-header text-center font-bold">
            Detalles del Pago
        </div>
        <div class="card-body">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="font-bold">Fecha del Documento:</label>
                    <p>{{ \Carbon\Carbon::parse($gasto->fecha_doc)->format('d-m-Y') }}</p>
                </div>
                <div>
                    <label class="font-bold">Documento:</label>
                    <p>{{ $gasto->factura ? 'Factura: '.$gasto->factura : 'Recibo: '.$gasto->recibo }}</p>
                </div>
                <div>
                    <label class="font-bold">Cuenta:</label>
                    <p>{{ $gasto->cuenta->nombre ?? '—' }}</p>
                </div>
                <div>
                    <label class="font-bold">Subcuenta:</label>
                    <p>{{ $gasto->subcuenta->nombre ?? '—' }}</p>
                </div>
                <div>
                    <label class="font-bold">Importe:</label>
                    <p>{{ number_format($gasto->importe, 2, '.', '') }}</p>
                </div>
                <div>
                    <label class="font-bold">Saldo Disponible en la Entrega:</label>
                    <p>{{ number_format($gasto->entrega->saldo ?? 0, 2, '.', '') }}</p>
                </div>
                <div class="col-span-2">
                    <label class="font-bold">Observaciones:</label>
                    <p>{{ $gasto->observaciones ?? '—' }}</p>
                </div>
            </div>
        </div>
        <div class="card-footer flex justify-between mt-2">
            <a href="{{ route('gastoscajachica.index') }}" class="btn btn-secondary">Volver</a>
        </div>
    </div>
@stop

@section('css')
<style>
/* Media Queries para responsividad */
@media (max-width: 768px) {
    .card {
        width: 95%;
        margin: 0 auto 1rem auto;
    }

    .grid.grid-cols-2 {
        display: grid !important;
        grid-template-columns: 1fr !important;
        gap: 1rem !important;
    }

    .col-span-2 {
        width: 100% !important;
    }

    .card-footer {
        flex-direction: column;
        gap: 0.5rem;
    }

    .card-footer .btn {
        width: 100%;
    }
}
</style>
@stop

@extends('layouts.app')

@section('content')
<div class="container-fluid px-2 px-md-4">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-8">

            <div class="card shadow-lg border-0">

                <div class="card-header text-white text-center py-3"
                    style="background-color:#134e4a;">
                    <h5 class="mb-0">IMPRESIÓN FINALIZADA</h5>
                </div>

                <div class="card-body text-center p-4">

                    <h3 class="mb-3">✅ Proceso completado</h3>

                    <p class="mb-4">
                        Todas las etiquetas del ingreso han sido impresas correctamente.
                        No existen más productos pendientes de impresión.
                    </p>

                    <div class="d-grid gap-2 col-md-6 mx-auto">
                        <a href="{{ url()->previous() }}"
                           class="btn text-white"
                           style="background-color:#134e4a;">
                            Volver
                        </a>
                    </div>

                </div>

            </div>

        </div>
    </div>
</div>
@endsection
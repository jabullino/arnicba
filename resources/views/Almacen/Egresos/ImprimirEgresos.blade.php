@extends('layouts.app')

@section('content')
<div class="container-fluid px-2 px-md-4">

    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">

            <div class="card shadow-lg border-0">

                <div class="card-header text-white text-center py-3"
                     style="background-color:#7c2d12;">
                    <h5 class="mb-0">LISTADO DE EGRESOS</h5>
                </div>

                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle text-center">

                            <thead style="background-color:#7c2d12; color:white;">
                                <tr>
                                    <th>ID</th>
                                    <th>Fecha</th>
                                    <th>Destinatario</th>
                                    <th>Creado</th>
                                    <th>Imprimir</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($egresos as $egreso)
                                    <tr>
                                        <td>{{ $egreso->id }}</td>

                                        <td>
                                            {{ \Carbon\Carbon::parse($egreso->fecha)->format('d/m/Y') }}
                                        </td>

                                        <td>
                                            {{ $egreso->destinatario->nombre ?? '-' }}
                                        </td>

                                        <td>
                                            {{ $egreso->created_at->format('d/m/Y H:i') }}
                                        </td>

                                        <td>
                                            <a href="{{ route('imprimirEgresos', ['id' => $egreso->id]) }}"
                                               class="btn btn-danger btn-sm">
                                                🖨 Imprimir
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5">
                                            No hay egresos registrados.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>

                        </table>
                    </div>

                    {{-- PAGINACIÓN --}}
                    <div class="d-flex justify-content-center mt-3">
                        {{ $egresos->links() }}
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection